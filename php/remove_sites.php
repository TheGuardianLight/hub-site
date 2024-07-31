<?php

require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection($dbConfig);
    $site_id = $_POST['site_id'];

    // Préparation de la requête SQL
    $sql = $conn->prepare("DELETE FROM sites WHERE site_id = :site_id");
    $sql->bindParam(':site_id', $site_id);

    try {
        $sql->execute();
        // Après la suppression, redirigez vers la page de gestion.
        header("Location: ../sites-categories.php");
        exit;
    } catch(PDOException $e) {
        echo "Erreur lors de la suppression du site: " . $e->getMessage();
    }

    $conn = null;
}

?>