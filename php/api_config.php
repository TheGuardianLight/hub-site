<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'apiKey' => $_ENV['API_KEY'],
    'authDomain' => $_ENV['AUTH_DOMAIN'],
    'projectId' => $_ENV['PROJECT_ID'],
    'storageBucket' => $_ENV['STORAGE_BUCKET'],
    'messagingSenderId' => $_ENV['MESSAGING_SENDER_ID'],
    'appId' => $_ENV['APP_ID'],
    'allowSignup' => $_ENV['ALLOW_SIGNUP']
];

// Lire le fichier JSON
$jsonData = file_get_contents('config.json');
$data = json_decode($jsonData, true);

?>