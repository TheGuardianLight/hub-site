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
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php require 'php/menu.php' ?>

<header class="bg-dark text-white text-center py-3">
    <h1>Bienvenue sur mon Hub</h1>
</header>
<main class="container my-5">
    <div id="hub-container">
        <?php foreach ($categories as $category): ?>
            <hr/>
            <h3><?php echo $category['cat_name']; ?>&nbsp;:</h3>
            <br/>
            <div class="row card-container">
                <?php foreach ($category['sites'] as $site): ?>
                    <?php
                    if ($site['site_tag'] == 'En ligne') {
                        $tagClass = 'bg-success';
                    } elseif ($site['site_tag'] == 'Indisponible') {
                        $tagClass = 'bg-danger';
                    }
                    ?>
                    <div class="col-md-4">
                        <a class="text-decoration-none text-dark" href="<?php echo $site['site_url']; ?>" hreflang="fr" target="_blank" rel="external">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $site['site_title']; ?>
                                        <?php if(!empty($site['site_tag'])): ?>
                                            | <span class="badge <?php echo $tagClass ?>"><?php echo $site['site_tag']; ?></span>
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
    </div>
</main>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 Veivneorul</p>
</footer>

</body>
</html>