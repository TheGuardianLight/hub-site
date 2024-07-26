<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'apiKey' => $_ENV['API_KEY'],
    'authDomain' => $_ENV['AUTH_DOMAIN'],
    'projectId' => $_ENV['PROJECT_ID'],
    'storageBucket' => $_ENV['STORAGE_BUCKET'],
    'messagingSenderId' => $_ENV['MESSAGING_SENDER_ID'],
    'appId' => $_ENV['APP_ID']
];

header('Content-Type: application/json');
echo json_encode($config);
