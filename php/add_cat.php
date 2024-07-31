<?php
global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection($dbConfig);
    $cat_name = $_POST['cat_name'];

    if (empty($cat_name)) {
        echo "Le nom de la catégorie est requis.";
        die();
    }

    // Préparation de la requête SQL
    $sql = $conn->prepare("INSERT INTO categorie (cat_name) VALUES (:cat_name)");
    $sql->bindParam(':cat_name', $cat_name);

    try {
        $sql->execute();
        echo "Nouvelle catégorie ajoutée avec succès.";
        // Après l'ajout, redirigez vers la page de gestion.
        header("Location: ../sites-categories.php");
        exit;
    } catch(PDOException $e) {
        echo "Une erreur s'est produite lors de l'ajout : " . $e->getMessage();
    }

    $conn = null;
}

?>