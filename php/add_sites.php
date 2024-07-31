<?php

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection($dbConfig);
    $site_title = $_POST['site_title'];
    $site_url = $_POST['site_url'];
    $site_tag = $_POST['site_tag'];
    $site_desc = $_POST['site_desc'];
    $cat_id = $_POST['cat_id'];

    // Préparation de la requête SQL
    $sql = $conn->prepare("INSERT INTO sites (site_title, site_url, site_tag, site_desc, cat_id) VALUES (:site_title, :site_url, :site_tag, :site_desc, :cat_id)");
    $sql->bindParam(':site_title', $site_title);
    $sql->bindParam(':site_url', $site_url);
    $sql->bindParam(':site_tag', $site_tag);
    $sql->bindParam(':site_desc', $site_desc);
    $sql->bindParam(':cat_id', $cat_id);

    try {
        $sql->execute();
        // Après l'ajout, redirigez vers la page de gestion.
        header("Location: /sites-categories.php");
        exit;
    } catch(PDOException $e) {
        echo "Une erreur s'est produite lors de l'ajout du site: " . $e->getMessage();
    }

    $conn = null;
}

?>