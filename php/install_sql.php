<?php
/**
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

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

        CREATE TABLE `categorie` (
            `cat_id` INT NOT NULL AUTO_INCREMENT,
            `cat_name` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`cat_id`)
        ) ENGINE=InnoDB;

        CREATE TABLE `sites` (
            `site_id` INT NOT NULL AUTO_INCREMENT,
            `site_title` VARCHAR(255) NOT NULL,
            `site_tag` VARCHAR(255) NOT NULL,
            `site_desc` TEXT,
            `site_url` VARCHAR(255) NOT NULL,
            `cat_id` INT,
            PRIMARY KEY (`site_id`),
            FOREIGN KEY (`cat_id`) REFERENCES `categorie`(`cat_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ";

    $connection->exec($sql);

    $envPath = __DIR__ . '/../.env';
    $env = [];

    if (file_exists($envPath)) {
        try {
            $dotenv = new Dotenv();
            $env = $dotenv->parse(file_get_contents($envPath), $envPath);
        } catch (PathException $exception) {
            throw new Exception('Erreur de chemin du fichier .env: ' . $exception->getMessage());
        }
    }

    $env['DB_HOST'] = $host;
    $env['DB_PORT'] = $port;
    $env['DB_NAME'] = $dbname;
    $env['DB_USER'] = $dbuser;
    $env['DB_PASSWORD'] = $dbpassword;

    $envData = '';
    foreach ($env as $key => $value) {
        $envData .= sprintf("%s=\"%s\"\n", $key, $value);
    }

    file_put_contents($envPath, $envData);

    touch('../install.lock');

    echo json_encode(['success' => 'La base de données a été configurée avec succès.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données', 'details' => 'Erreur de base de données: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur d\'installation', 'details' => $e->getMessage()]);
}