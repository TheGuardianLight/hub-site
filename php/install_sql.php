<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

$host = $_POST["dbhost"];
$port = $_POST["dbport"];
$dbname = $_POST["dbname"];
$dbuser = $_POST["dbuser"];
$dbpassword = $_POST["dbpassword"];

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

try {
    $connection = new PDO($dsn, $dbuser, $dbpassword);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Votre code SQL ici ...

    $envPath = __DIR__ . '/../.env';
    $env = [];

    if (file_exists($envPath)) {
        try {
            $dotenv = new Dotenv();
            $env = $dotenv->parse(file_get_contents($envPath), $envPath);
        } catch (PathException $exception) {
            // Gérer l'exception si nécessaire, enregistrez sûrement quelque part
        }
    }

    // Code pour gérer les variables d'environnement ...

    echo json_encode(['success' => 'La base de données a été configurée avec succès.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage(), 'details' => $e->getTraceAsString()]);
} catch (PathException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de chemin du fichier .env: ' . $e->getMessage(), 'details' => $e->getTraceAsString()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur inattendue: ' . $e->getMessage(), 'details' => $e->getTraceAsString()]);
}