<?php

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

$conn = getDbConnection($dbConfig);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetching the form data
    $cat_id = $_POST['cat_id'];
    $new_cat_name = $_POST['new_cat_name'];

    // Building the SQL query
    $sql = "UPDATE categorie 
            SET cat_name = :cat_name
            WHERE cat_id = :cat_id";

    // Preparing statement and binding parameters
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cat_name', $new_cat_name);
    $stmt->bindParam(':cat_id', $cat_id);

    // Executing the query
    $stmt->execute();

    // Redirection to management page
    header("Location: ../gestion.php");
}
?>