<?php
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

// Exemple d'utilisation
$url = "https://roleplay.neodraco.fr";
$http_code = check_http_status($url);

if ($http_code == 200) {
    echo "HTTP 200: OK";
} elseif ($http_code == 201) {
    echo "HTTP 201: Created";
} else {
    echo "HTTP $http_code: Other status";
}
?>
