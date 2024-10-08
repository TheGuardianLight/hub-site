<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php

global $dbConfig;
global $tagClass;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';

$conn = getDbConnection($dbConfig);

// Récupération des catégories de la base de données
$sql = "SELECT * FROM categorie";
$resultCategories = $conn->query($sql);

$categories = [];
while ($row = $resultCategories->fetch()) {
    // Pour chaque catégorie, on récupère aussi les sites correspondants
    $sqlSites = "SELECT * FROM sites WHERE cat_id = " . $row['cat_id'];
    $resultSites = $conn->query($sqlSites);
    $sites = $resultSites->fetchAll();
    $categories[] = ['cat_id' => $row['cat_id'], 'cat_name' => $row['cat_name'], 'sites' => $sites];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<header class="bg-dark text-white text-center py-3">
    <h1>Bienvenue sur mon Hub</h1>
</header>
<main class="container my-5">
    <div id="hub-container">
        <?php if (empty($categories)): ?>
            <div class="alert alert-info" role="alert">
                Aucune catégorie enregistrée.
            </div>
        <?php endif; ?>

        <?php
        $noSites = true;
        if (!empty($categories)):
            foreach ($categories as $category):
                if (!empty($category['sites'])) {
                    $noSites = false;
                    break;
                }
            endforeach;
        endif;
        ?>

        <?php if ($noSites): ?>
            <div class="alert alert-info" role="alert">
                Aucun site enregistré.
            </div>
        <?php endif; ?>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <hr/>
                <h3><?php echo $category['cat_name']; ?>&nbsp;:</h3>
                <br/>
                <div class="row card-container">
                    <?php foreach ($category['sites'] as $site): ?>
                        <?php
                        switch (true) {
                            case $site['site_tag'] === 'En ligne':
                                $tagClass = 'bg-success';
                                break;
                            case $site['site_tag'] === 'Erreur DNS':
                            case str_starts_with($site['site_tag'], 'Erreur client'):
                            case str_starts_with($site['site_tag'], 'Erreur serveur'):
                            case str_starts_with($site['site_tag'], 'Inaccessible'):
                                $tagClass = 'bg-danger';
                                break;
                            default:
                                $tagClass = 'bg-warning'; // Utilisez cette classe pour les cas non prévus
                                break;
                        }
                        ?>
                        <div class="col-md-4">
                            <a class="text-decoration-none text-dark" href="<?php echo $site['site_url']; ?>" hreflang="fr" target="_blank" rel="external">
                                <div class="card mb-4 cursor">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $site['site_title']; ?>
                                            <?php if (!empty($site['site_tag'])): ?>
                                                | <span class="badge <?php if (isset($tagClass)) { echo $tagClass; } ?>"><?php echo $site['site_tag']; ?></span>
                                            <?php endif; ?>
                                        </h5>
                                        <p class="card-text"><?php echo $site['site_desc']; ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require 'php/footer.php' ?>

</body>
</html>