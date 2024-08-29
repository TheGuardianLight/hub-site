<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

declare(strict_types=1);

global $dbConfig;
require '../vendor/autoload.php';
require '../php/api_config.php';

/**
 * Fetch all sites from the database.
 *
 * @param PDO $connection
 * @return array
 */
function getSites(PDO $connection): array {
    try {
        $stmt = $connection->query("SELECT site_id, site_url FROM sites");
        if ($stmt === false) {
            throw new Exception("Query failed: SELECT site_id, site_url FROM sites");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Error fetching sites: ', $e->getMessage(), "\n";
        return [];
    }
}

/**
 * Update the site_tag for a specific site in the database.
 *
 * @param PDO $connection
 * @param int $siteId
 * @param string $siteTag
 * @return void
 */
function updateSiteTag(PDO $connection, int $siteId, string $siteTag): void {
    try {
        $stmt = $connection->prepare("UPDATE sites SET site_tag = :site_tag WHERE site_id = :id");
        if ($stmt === false) {
            throw new Exception("Preparation of update query failed");
        }
        $stmt->execute(['site_tag' => $siteTag, 'id' => $siteId]);
    } catch (Exception $e) {
        echo 'Error updating site tag: ', $e->getMessage(), "\n";
    }
}

/**
 * Check if a URL is reachable and get the HTTP status code.
 *
 * @param string $url
 * @return array
 */
function checkUrl(string $url): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $success = curl_exec($ch);

    if ($success === false) {
        $errorCode = curl_errno($ch);
        curl_close($ch);
        return ['httpCode' => 0, 'errorCode' => $errorCode];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['httpCode' => $httpCode, 'errorCode' => 0];
}

/**
 * Map HTTP status codes and cURL errors to site_tag values.
 *
 * @param int $httpCode
 * @param int $errorCode
 * @return string
 */
function getSiteTagFromHttpResponse(int $httpCode, int $errorCode): string {
    if ($errorCode === CURLE_COULDNT_RESOLVE_HOST) {
        return 'Erreur DNS';
    }
    if ($httpCode >= 200 && $httpCode <= 302) {
        return 'En ligne';
    } elseif ($httpCode >= 400 && $httpCode < 500) {
        return 'Erreur client ' . $httpCode;
    } elseif ($httpCode >= 500 && $httpCode < 600) {
        return 'Erreur serveur ' . $httpCode;
    } else {
        return 'Inaccessible ' . $httpCode;
    }
}

try {
    $connection = getDbConnection($dbConfig);
    $sites = getSites($connection);

    foreach ($sites as $site) {
        echo "Test du site : " . $site['site_url'] . "\n";
        $response = checkUrl($site['site_url']); // Utilisation de la clé correcte ici
        $siteTag = getSiteTagFromHttpResponse($response['httpCode'], $response['errorCode']);
        updateSiteTag($connection, (int)$site['site_id'], $siteTag);
        echo "Site " . $site['site_url'] . " testé avec le code http " . $response['httpCode'] . "\n";
    }

    echo "Les statuts des sites ont été mis à jour avec succès.\n";

} catch (Exception $e) {
    echo 'Connection error: ', $e->getMessage(), "\n";
} finally {
    $connection = null; // Assurez-vous de fermer la connexion
}
?>