<?php
session_start();
session_destroy();
header('Location: ../index.php'); // vous pouvez le rediriger vers la page que vous voulez
exit;
?>