<?php

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
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container">
    <h1 class="my-4">Gérer les catégories</h1>

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

</div>

<?php
$conn = null; // fermeture de la connexion à la base de données
?>

</body>
</html>