<?php
session_start();
session_destroy();
header('Location: ../login.php'); // vous pouvez le rediriger vers la page que vous voulez
exit;