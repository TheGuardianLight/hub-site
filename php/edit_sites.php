<?php

global $dbConfig;
require __DIR__ . '/../vendor/autoload.php';
require 'api_config.php';

$conn = getDbConnection($dbConfig);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetching the form data
    $site_id = $_POST['site_id'];
    $new_site_title = $_POST['new_site_title'];
    $new_site_url = $_POST['new_site_url'];
    $new_site_desc = $_POST['new_site_desc'];
    $new_cat_id = $_POST['cat_id'];

    // Building the SQL query
    $sql = "UPDATE sites 
            SET site_title = :site_title, 
                site_url = :site_url, 
                site_desc = :site_desc,
                cat_id = :cat_id
            WHERE site_id = :site_id";

    // Preparing statement and binding parameters
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':site_title', $new_site_title);
    $stmt->bindParam(':site_url', $new_site_url);
    $stmt->bindParam(':site_desc', $new_site_desc);
    $stmt->bindParam(':cat_id', $new_cat_id);
    $stmt->bindParam(':site_id', $site_id);

    // Executing the query
    $stmt->execute();

    // Redirection to management page
    header("Location: ../sites-categories.php");
}