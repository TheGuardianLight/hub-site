<?php

$host = $_POST["dbhost"];
$port = $_POST["dbport"];
$dbname = $_POST["dbname"];
$dbuser = $_POST["dbuser"];
$dbpassword = $_POST["dbpassword"];

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

// Crée une connexion PDO
try {
    $connection = new PDO($dsn, $dbuser, $dbpassword);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE TABLE `users` (
            `username` VARCHAR(50) NOT NULL PRIMARY KEY,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB;

        CREATE TABLE `user_info` (
            `username` VARCHAR(50),
            `first_name` VARCHAR(100),
            `last_name` VARCHAR(100),
            `email` VARCHAR(255),
            PRIMARY KEY (`username`),
            FOREIGN KEY (`username`) REFERENCES `users`(`username`) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ";

    $connection->exec($sql);

    // Crée un nouveau fichier .env ou met à jour un existant
    $envData = sprintf("DB_HOST=%s\nDB_PORT=%s\nDB_NAME=%s\nDB_USER=%s\nDB_PASSWORD=%s", $host, $port, $dbname, $dbuser, $dbpassword);
    file_put_contents('.env', $envData);

    echo json_encode(['success' => 'La base de données a été configurée avec succès.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
}