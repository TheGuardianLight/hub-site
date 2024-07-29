<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';

// Lire le fichier JSON
$jsonData = file_get_contents('config.json');
$data = json_decode($jsonData, true);
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
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-auth.js"></script>
</head>
<body>
<header class="bg-dark text-white text-center py-3">
    <h1>Bienvenue sur mon Hub</h1>
</header>
<main class="container my-5">
    <div id="hub-container">
        <?php foreach ($data['categories'] as $category): ?>
            <hr/>
            <h3><?php echo $category['name']; ?></h3>
            <br/>
            <div class="row card-container">
                <?php foreach ($category['cards'] as $card): ?>
                    <?php if ($card['siteStatus'] == 'En ligne') {
                        $tagClass = 'bg-success';
                    } elseif ($card['siteStatus'] == 'Indisponible') {
                        $tagClass = 'bg-danger';
                    }; ?>
                    <div class="col-md-4">
                        <a class="text-decoration-none text-dark" href="<?php echo $card['url']; ?>">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $card['siteTitle']; ?> | <span
                                                class="badge <?php echo $tagClass ?>"><?php echo $card['siteStatus']; ?></span>
                                    </h5>
                                    <p class="card-text"><?php echo $card['siteDescription']; ?></p>
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

<script crossorigin="anonymous"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js">
</script>
<script crossorigin="anonymous"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js">
</script>

</body>
</html>