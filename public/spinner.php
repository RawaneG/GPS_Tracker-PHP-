<?php
require 'database.php';
$conn = connexion();
session_start();
(!isset($_SESSION['username'])) ?  header("Location: index.php") : header('Refresh: 1; URL = map.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/spinner.css" />
</head>

<body>
  <div class="blob-4"></div>
  <h3>Chargement ...</h3>
</body>

</html>