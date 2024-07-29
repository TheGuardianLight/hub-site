<?php

$dotenv = Dotenv\Dotenv::createImmutable('.\\');
$dotenv->load();

$dbConfig = [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
];

$config = [
    'allowSignup' => $_ENV['ALLOW_SIGNUP'],
];

// Function to get the database connection
function getDbConnection($dbConfig) {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};port={$dbConfig['port']}";
    $user = $dbConfig['user'];
    $password = $dbConfig['password'];

    try {
        $connection = new PDO($dsn, $user, $password);
    } catch (PDOException $exception) {
        echo "Connection failed: " . $exception->getMessage();
    }

    return $connection;
}

?>