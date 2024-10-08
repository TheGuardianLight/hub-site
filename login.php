<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php
global $dbConfig, $config;
require 'vendor/autoload.php';
require 'php/api_config.php';
require 'php/get_login.php';

function getLoginFormError()
{
    global $message, $messageType;

    if (empty($message)) return;

    echo "<div class=\"d-grid gap-2 mt-3 alert alert-$messageType\" role=\"alert\">$message</div>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <h2 class="text-center my-4">Connexion</h2>
            <form action="login.php" method="POST">
                <?php if (isset($loginError)) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlentities($loginError); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Mot de passe" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between gap-2 mt-3">
                    <button type="submit" class="btn btn-primary w-50" name="login">Se connecter</button>
                    <?php if ($config['allowSignup'] == "true"): ?>
                        <button type="button" class="btn btn-secondary w-50" onclick="location.href='register.php'">
                            S'inscrire
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>