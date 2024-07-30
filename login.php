<?php
global $dbConfig, $config;
require 'vendor/autoload.php';
require 'php/api_config.php';

// Démarrer une nouvelle session
session_start();

$message = '';

// Connexion à la base de donnée
if(isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)){
        $connection = getDbConnection($dbConfig);
        // Check if the entered username/email is present in the database
        $records = $connection->prepare('SELECT username,email,password FROM users WHERE username = :username OR email = :email');
        $records->bindParam(':username', $username);
        $records->bindParam(':email', $username);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        if($results && password_verify($password, $results['password'])){
            // Stocker les informations sur l'utilisateur dans la session
            $_SESSION['username'] = $results['username'];
            $_SESSION['email'] = $results['email'];

            // Rediriger l'utilisateur vers hub.php
            header("Location: hub.php");
        } else {
            $message = "Erreur : Les informations d'identification ne correspondent pas";
            $messageType = 'danger';
        }
    } else {
        $message = 'Veuillez remplir les deux champs';
        $messageType = 'warning';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <h2 class="text-center my-4">Connexion</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>

                <div class="form-group mt-2">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" name="login">Se connecter</button>
                    <?php if ($config['allowSignup'] == "true"): ?>
                        <button type="button" class="btn btn-secondary" onclick="location.href='register.php'">S'inscrire</button>
                    <?php endif; ?>
                </div>

                <?php if(!empty($message)): ?>
                    <p class="text-danger"><?= $message ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?php if(!empty($message)): ?>
    <div class="alert alert-<?= $messageType ?>" role="alert"><?= $message ?></div>
<?php endif; ?>
</body>
</html>