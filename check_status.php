<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Lire le fichier JSON
$jsonData = file_get_contents('config.json');
$data = json_decode($jsonData, true);

if (!function_exists('check_http_status')) {
    function check_http_status($url) {
        // Initialiser cURL
        $ch = curl_init($url);

        // Définir les options cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);    // Inclure l'en-tête de la réponse
        curl_setopt($ch, CURLOPT_NOBODY, true);    // Ne pas inclure le corps de la réponse

        // Exécuter la requête cURL
        $response = curl_exec($ch);

        // Récupérer le code de statut HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fermer cURL
        curl_close($ch);

        return $http_code;
    }
}

// Vérifier le statut HTTP de chaque URL
foreach ($data['categories'] as $categoryKey => $category) {
    foreach ($category['cards'] as $cardKey => $card) {
        $url = $card['url'];
        $http_code = check_http_status($url);

        if ($http_code == 200) {
            $data['categories'][$categoryKey]['cards'][$cardKey]['siteStatus'] = 'En ligne';
        } else {
            $data['categories'][$categoryKey]['cards'][$cardKey]['siteStatus'] = 'Indisponible';
        }
    }
}

// Écrire les données mises à jour dans le fichier config.json
file_put_contents('config.json', json_encode($data));
?>
