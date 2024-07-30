<?php

global $dbConfig;
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

// Récupération des sites de la base de données
$sql = "SELECT * FROM sites";
$resultSites = $conn->query($sql);

// Convertir les résultats en tableau associatif pour faciliter l'accès
$categories = [];
while ($row = $resultCategories->fetch()) {
    $categories[$row['cat_id']] = $row['cat_name'];
}

$sites = [];
while ($row = $resultSites->fetch()) {
    $sites[] = $row;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet"/>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container">
    <!-- Tabs -->
    <ul class="nav nav-pills my-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="categories-tab" data-bs-toggle="pill" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="true">Gérer les catégories</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sites-tab" data-bs-toggle="pill" data-bs-target="#sites" type="button" role="tab" aria-controls="sites" aria-selected="false">Gérer les sites</button>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content" id="adminTabsContent">
        <!-- Catégories tab -->
        <div class="tab-pane fade show active" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="row">
                <?php foreach($categories as $cat_id => $cat_name): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $cat_name; ?></h3>
                                <form method='post' action='php/remove_cat.php'>
                                    <input type='hidden' name='cat_id' value='<?php echo $cat_id; ?>' />
                                    <button type='submit' class='btn btn-danger'>Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Formulaire pour ajouter une nouvelle catégorie -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Ajouter une catégorie</h3>
                    <form class="mt-3" method="post" action="php/add_cat.php">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="cat_name" placeholder="Nom de la nouvelle catégorie">
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter une catégorie</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sites tab -->
        <div class="tab-pane fade" id="sites" role="tabpanel" aria-labelledby="sites-tab">
            <!-- Formulaire pour ajouter un nouveau site -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Ajouter un site</h3>
                    <form class="mt-4" method="post" action="php/add_sites.php">
                        <div class="mb-3">
                            <label for="site_title" class="form-label">Titre du site</label>
                            <input type="text" class="form-control" name="site_title" id="site_title" placeholder="Titre du nouveau site">
                        </div>
                        <div class="mb-3">
                            <label for="site_url" class="form-label">URL du site</label>
                            <input type="text" class="form-control" name="site_url" id="site_url" placeholder="URL du nouveau site">
                        </div>
                        <div class="mb-3">
                            <label for="site_tag" class="form-label">Tag pour le site</label>
                            <input type="text" class="form-control" name="site_tag" id="site_tag" placeholder="Tag pour le nouveau site">
                        </div>
                        <div class="mb-3">
                            <label for="site_desc" class="form-label">Description du site</label>
                            <input type="text" class="form-control" name="site_desc" id="site_desc" placeholder="Description du nouveau site">
                        </div>
                        <div class="mb-3">
                            <label for="cat_id" class="form-label">Catégorie</label>
                            <select name="cat_id" id="cat_id" class="form-control">
                                <?php foreach($categories as $cat_id => $cat_name): ?>
                                    <option value="<?php echo $cat_id; ?>"><?php echo $cat_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter un site</button>
                    </form>
                </div>
            </div>

            <!-- Ajouter une section pour afficher les sites existants -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Sites existants</h3>
                    <?php foreach($sites as $site): ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $site['site_title']; ?></h5>
                                <p><strong>URL: </strong><?php echo $site['site_url']; ?></p>
                                <p><strong>Tag: </strong><?php echo $site['site_tag']; ?></p>
                                <p><strong>Description: </strong><?php echo $site['site_desc']; ?></p>
                                <p><strong>Catégorie: </strong><?php echo $categories[$site['cat_id']] ?? 'N/A'; ?></p>
                                <form method='post' action='php/remove_sites.php'>
                                    <input type='hidden' name='site_id' value='<?php echo $site['site_id']; ?>' />
                                    <button type='submit' class='btn btn-danger'>Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn = null; // fermeture de la connexion à la base de données
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>