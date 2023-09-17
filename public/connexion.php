<?php

require_once('database.php');
$conn = connexion();
session_start();
$username = $_POST['user'];
$password = $_POST['pass'];
$sql = "SELECT * FROM users WHERE Email = '$username' and Password = '$password'";
$statement = $conn->prepare($sql);
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
$count = $statement->rowCount();
if ($count == 1) {
    $_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['role'] = $row["role"];
    $_SESSION['name'] = $row["Prenom"] . " " . $row["Nom"];
    $_SESSION['username'] = $row["ID_user"];
    $_SESSION['numero_compte'] = $row["Numero_compte"];
    $row['Role'] === 2 ?  header('Location: spinner.php') :  header('Location: gestion_user.php');
} else {
    exit;
}
