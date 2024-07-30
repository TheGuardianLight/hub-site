<?php

global $dbConfig;
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
require 'php/api_config.php';
require_once 'php/user_management.php';

$userInfo = getUserInfo($dbConfig, $_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    updateUserInfo($dbConfig, $_SESSION['username'], $email, $first_name, $last_name, $password);
    // Update the $userInfo variable after the update so the form displays updated values
    $userInfo = getUserInfo($dbConfig, $_SESSION['username']);
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>
<body>

<?php require 'php/menu.php' ?>

<div class="container my-3">
    <form method="post">
        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="username" class="form-control" id="username" name="username" value="<?php echo $userInfo['username'] ?>" disabled>
        </div>
        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userInfo['email'] ?>">
        </div>
        <!-- First Name -->
        <div class="mb-3">
            <label for="first_name" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $userInfo['first_name'] ?>">
        </div>
        <!-- Last Name -->
        <div class="mb-3">
            <label for="last_name" class="form-label">Nom de famille</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $userInfo['last_name'] ?>">
        </div>
        <!-- Password  -->
        <div class='mb-3'>
            <label class="form-label" for='new_password'>New password</label>
            <input type='password' class="form-control" id='new_password' name='password'>
        </div>
        <!-- Confirm Password -->
        <div class='mb-3'>
            <label class="form-label" for='confirm_password'>Confirm New password</label>
            <input type='password' class="form-control" id='confirm_password' name='confirm_password'>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php require 'php/footer.php'?>

</body>
</html>