<?php
/*
 * Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
 */

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDbConnection($dbConfig);
    $cat_id = $_POST['cat_id'];

    if (empty($cat_id)) {
        echo "L'ID de la catégorie est requis.";
        die();
    }

    // Prepare the SQL delete query
    $sql = $conn->prepare("DELETE FROM categorie WHERE cat_id = :cat_id");
    $sql->bindParam(':cat_id', $cat_id);

    try {
        $sql->execute();
        echo "Catégorie supprimée avec succès.";
        // After deletion, redirect back to the manage page
        header("Location: ../sites-categories.php");
        exit;
    } catch (PDOException $e) {
        echo "Une erreur s'est produite lors de la suppression : " . $e->getMessage();
    }

    $conn = null;
}