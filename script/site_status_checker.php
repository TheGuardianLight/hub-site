<?php

/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

global $dbConfig;
require '../vendor/autoload.php';
require '../php/api_config.php';

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Obtenir la date et l'heure actuelles au début du script
$startTime = date('Y-m-d H:i:s');

/**
 * Fonction pour envoyer un email avec PHPMailer
 *
 * @param array $errors
 * @return void
 */
function sendErrorEmail(array $errors): void {
    $mail = new PHPMailer(true);

    try {
        // Configurer le serveur SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        // Configurer le destinataire
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress('veivneorul@neodraco.fr');

        // Configurer le contenu de l'email
        $mail->isHTML(false);
        $mail->Subject = 'Problèmes détectés lors de la vérification des sites';
        $mail->CharSet = 'UTF-8';

        $body = "Des erreurs ont été rencontrées lors de la vérification des sites:\n\n";
        foreach ($errors as $error) {
            $body .= "Site: " . utf8_encode($error['site_url']) . "\n";
            $body .= "Erreur: " . utf8_encode($error['error_message']) . "\n";
            $body .= "-------\n";
        }
        $mail->Body = $body;

        // Envoyer l'email
        $mail->send();
        echo 'Email envoyé avec succès.';
    } catch (Exception $e) {
        echo "Erreur: L'envoi de l'email a échoué. Mailer Error: {$mail->ErrorInfo}";
    }
}

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

function getSiteTagFromHttpResponse(int $httpCode, int $errorCode): array {
    $dnsError = false;
    $siteTag = '';

    if ($errorCode === CURLE_COULDNT_RESOLVE_HOST) {
        $dnsError = true;
        $siteTag = 'Erreur DNS';
    } elseif ($httpCode >= 200 && $httpCode <= 302) {
        $siteTag = 'En ligne';
    } elseif ($httpCode >= 400 && $httpCode < 500) {
        $siteTag = 'Erreur client ' . $httpCode;
    } elseif ($httpCode >= 500 && $httpCode < 600) {
        $siteTag = 'Erreur serveur ' . $httpCode;
    } else {
        $siteTag = 'Inaccessible ' . $httpCode;
    }

    return ['siteTag' => $siteTag, 'dnsError' => $dnsError];
}

try {
    $connection = getDbConnection($dbConfig);
    $sites = getSites($connection);
    $errors = [];

    foreach ($sites as $site) {
        echo "Test du site : " . $site['site_url'] . "\n";
        $response = checkUrl($site['site_url']);
        $result = getSiteTagFromHttpResponse($response['httpCode'], $response['errorCode']);
        $siteTag = $result['siteTag'];
        $dnsError = $result['dnsError'];

        updateSiteTag($connection, (int)$site['site_id'], $siteTag);
        echo "Site " . $site['site_url'] . " testé avec le code http " . $response['httpCode'] . "\n";

        // Collecte les erreurs HTTP 400-599
        if (($response['httpCode'] >= 400 && $response['httpCode'] < 600) || $dnsError) {
            $errors[] = [
                'site_url' => $site['site_url'],
                'error_message' => $siteTag,
            ];
            echo "Erreur collectée pour le site " . $site['site_url'] . " : " . $siteTag . "\n";
        }
    }

    echo "Les statuts des sites ont été mis à jour avec succès.\n";

    // Envoie un e-mail si des erreurs sont collectées
    if (!empty($errors)) {
        echo "Envoi de l'email avec les erreurs collectées.\n";
        sendErrorEmail($errors);
    }

} catch (Exception $e) {
    echo 'Connection error: ', $e->getMessage(), "\n";
} finally {
    $connection = null; // Assurez-vous de fermer la connexion
}
?>;