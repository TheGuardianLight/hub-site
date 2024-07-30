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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container">
    <!-- Tabs -->
    <ul class="nav nav-pills my-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categories-tab" data-bs-toggle="pill" data-bs-target="#categories"
                    type="button" role="tab" aria-controls="categories">Gérer les catégories
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sites-tab" data-bs-toggle="pill" data-bs-target="#sites" type="button"
                    role="tab" aria-controls="sites">Gérer les sites
            </button>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content" id="adminTabsContent">
        <!-- Catégories tab -->
        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <!-- Formulaire pour ajouter une nouvelle catégorie -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Ajouter une catégorie</h3>
                    <form class="mt-3" method="post" action="php/add_cat.php">
                        <div class="mb-3">
                            <label for="cat_name" class="form-label">Nom de la catégorie</label>
                            <input type="text" class="form-control" name="cat_name" id="cat_name"
                                   placeholder="Nom de la nouvelle catégorie"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter une catégorie</button>
                    </form>
                </div>
            </div>

            <!-- Ajout d'une section pour modifier une catégorie existante -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Modifier une catégorie</h3>
                    <form class="mt-3 row" method="post" action="php/edit_cat.php">
                        <!-- Select pour choisir la catégorie à modifier -->
                        <div class="mb-3 col-md-6">
                            <label for="edit_cat_id" class="form-label h5 mb-3 border-bottom pb-1 border-primary">Choisir la catégorie</label>
                            <select name="cat_id" id="edit_cat_id" class="form-control">
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach($categories as $cat_id => $cat_name): ?>
                                    <option value="<?php echo $cat_id; ?>"><?php echo $cat_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Input pour entrer le nouveau nom -->
                        <div class="mb-3 col-md-6">
                            <label for="new_cat_name" class="form-label h5 mb-3 border-bottom pb-1 border-danger">Nouveau nom</label>
                            <input type="text" class="form-control" name="new_cat_name" id="new_cat_name" placeholder="Nouveau nom de la catégorie">
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Modifier la catégorie</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php foreach ($categories as $cat_id => $cat_name): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $cat_name; ?></h3>
                                <form method='post' action='php/remove_cat.php'>
                                    <input type='hidden' name='cat_id' value='<?php echo $cat_id; ?>'/>
                                    <button type='submit' class='btn btn-danger'>Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Sites tab -->
        <div class="tab-pane fade" id="sites" role="tabpanel" aria-labelledby="sites-tab">
            <!-- Formulaire pour ajouter un nouveau site -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Ajouter un site</h3>
                    <form class="mt-4 row" method="post" action="php/add_sites.php">
                        <div class="col-md-3 mb-3">
                            <label for="site_title" class="form-label">Titre de la carte</label>
                            <input type="text" class="form-control" name="site_title" id="site_title"
                                   placeholder="Titre du nouveau site">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="site_url" class="form-label">URL du site</label>
                            <input type="text" class="form-control" name="site_url" id="site_url"
                                   placeholder="URL du nouveau site">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="site_tag" class="form-label">Tag pour le site</label>
                            <input type="text" class="form-control" name="site_tag" id="site_tag"
                                   placeholder="Tag pour le nouveau site">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="site_desc" class="form-label">Description du site</label>
                            <input type="text" class="form-control" name="site_desc" id="site_desc"
                                   placeholder="Description du nouveau site">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cat_id" class="form-label">Catégorie</label>
                            <select name="cat_id" id="cat_id" class="form-control">
                                <?php foreach ($categories as $cat_id => $cat_name): ?>
                                    <option value="<?php echo $cat_id; ?>"><?php echo $cat_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Ajouter un site</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ajout d'une section pour modifier un site existant -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Modifier un site</h3>
                    <form id="editSiteForm" class="mt-4 row" method="post" action="php/edit_sites.php">
                        <!-- Select pour choisir le site à modifier -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_site_id" class="form-label h5 mb-3 border-bottom pb-1 border-primary">Choisir
                                le site</label>
                            <select name="site_id" id="edit_site_id" class="form-control">
                                <option value="">Sélectionner un site</option>
                                <?php foreach ($sites as $site): ?>
                                    <option value="<?php echo $site['site_id']; ?>"><?php echo $site['site_title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Inputs pour modifier les infos du site -->
                        <div class="col-md-3 mb-3">
                            <label for="new_site_title" class="form-label h5 mb-3 border-bottom pb-1 border-danger">Nouveau
                                titre</label>
                            <input type="text" class="form-control" name="new_site_title" id="new_site_title"
                                   placeholder="Nouveau titre du site">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="new_site_url" class="form-label h5 mb-3 border-bottom pb-1 border-danger">Nouvelle
                                URL</label>
                            <input type="text" class="form-control" name="new_site_url" id="new_site_url"
                                   placeholder="Nouvelle URL du site">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="new_site_desc" class="form-label h5 mb-3 border-bottom pb-1 border-danger">Nouvelle
                                description</label>
                            <input type="text" class="form-control" name="new_site_desc" id="new_site_desc"
                                   placeholder="Nouvelle description du site">
                        </div>
                        <!-- Select pour modifier la catégorie du site -->
                        <div class="col-md-3 mb-3">
                            <label for="new_cat_id" class="form-label h5 mb-3 border-bottom pb-1 border-danger">Nouvelle
                                catégorie</label>
                            <select name="cat_id" id="new_cat_id" class="form-control">
                                <?php foreach ($categories as $cat_id => $cat_name): ?>
                                    <option value="<?php echo $cat_id; ?>"><?php echo $cat_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Modifier le site</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ajouter une section pour afficher les sites existants -->
            <div class="card my-4">
                <div class="card-body">
                    <h3 class="card-title">Sites existants</h3>
                    <div class="row">
                        <?php foreach ($sites as $site): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $site['site_title']; ?></h5>
                                        <p><strong>URL: </strong><?php echo $site['site_url']; ?></p>
                                        <p><strong>Tag: </strong><span class="badge bg-info"><?php echo $site['site_tag']; ?></span></p>
                                        <p><strong>Description: </strong><?php echo $site['site_desc']; ?></p>
                                        <p><strong>Catégorie: </strong><?php echo $categories[$site['cat_id']] ?? 'N/A'; ?></p>
                                        <form method='post' action='php/remove_sites.php'>
                                            <input type='hidden' name='site_id' value='<?php echo $site['site_id']; ?>'/>
                                            <button type='submit' class='btn btn-danger'>Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn = null; // fermeture de la connexion à la base de données
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>

<script>
    // Initialiser les données des sites
    var sites = <?php echo json_encode($sites); ?>;

    // Fonction pour mettre à jour les valeurs des champs du formulaire en fonction du site sélectionné
    function updateFormValues() {
        var selectedSiteId = document.getElementById("edit_site_id").value;
        var selectedSite = sites.find(site => +site.site_id === +selectedSiteId);

        document.getElementById("new_site_title").value = selectedSite ? selectedSite.site_title : '';
        document.getElementById("new_site_url").value = selectedSite ? selectedSite.site_url : '';
        document.getElementById("new_site_desc").value = selectedSite ? selectedSite.site_desc : '';
        document.getElementById("new_cat_id").value = selectedSite ? selectedSite.cat_id : '';
    }

    // Ajouter un écouteur d'événement pour mettre à jour les valeurs du formulaire chaque fois que le site sélectionné change
    document.getElementById("edit_site_id").addEventListener("change", updateFormValues);

    // Appeler la fonction une fois au chargement de la page pour remplir les valeurs initiales
    window.onload = updateFormValues;
</script>

<script>
    // tab switcher function
    function switchTab() {
        const tabs = Array.from(document.querySelectorAll('#adminTabs .nav-link'));
        const activeTabName = localStorage.getItem('activeTab');
        const activeTab = tabs.find(tab => tab.id === activeTabName);
        if (activeTab) {
            const tab = new bootstrap.Tab(activeTab);
            tab.show();
        }
    }

    // tab click listener
    const adminTabsEl = document.querySelector('#adminTabs')
    adminTabsEl.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('nav-link')) {
            localStorage.setItem('activeTab', target.id);
        }
    });

    // call on page load
    document.addEventListener("DOMContentLoaded", function () {
        switchTab();
    });
</script>

<?php require 'php/footer.php'?>

</body>
</html>