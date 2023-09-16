<?php

include('database.php');
include('helper.php');

$conn = connexion();
$Email = $_POST['inputEmail'];
$emailExistsQuery = "SELECT COUNT(*) AS count FROM users WHERE Email = :email";
$stmt = $conn->prepare($emailExistsQuery);
$stmt->bindParam(':email', $Email, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$emailExists = $row['count'];

if ($emailExists > 0) {
    $error_message = "Cet email existe déjà. Veuillez en utiliser un autre.";
    header("Location: add_user.php?error=" . urlencode($error_message));
} else {
    $role = $_POST['Role'];
    $Date = $_POST['Date'];
    $Nom = $_POST['inputNom'];
    $Telepohne = $_POST['inputTel'];
    $Prenom = $_POST['inputPrenom'];
    $Numero_compte = $_POST['Numero_compte'];
    $formattedDate = date("Y-m-d H:i:s", strtotime($Date));

    $sql = "INSERT INTO users (Nom, Prenom, Email, Password, Role, Telephone, Expiration_date) VALUES (:nom, :prenom, :email, 'admin', :role, :telephone, :expiration_date)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $Nom, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':email', $Email, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $Prenom, PDO::PARAM_STR);
    $stmt->bindParam(':telephone', $Telepohne, PDO::PARAM_STR);
    $stmt->bindParam(':expiration_date', $formattedDate, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $lastUserId = $conn->lastInsertId();

        $sqlNumeroCompte = "INSERT INTO numero_compte (Libelle) VALUES (:libelle)";
        $stmtNumeroCompte = $conn->prepare($sqlNumeroCompte);
        $stmtNumeroCompte->bindParam(':libelle', $Numero_compte, PDO::PARAM_STR);

        if ($stmtNumeroCompte->execute()) {
            $lastNumeroCompteId = $conn->lastInsertId();
            $updateSql = "UPDATE users u SET u.Numero_compte = :numero_compte_id WHERE u.ID_user = :user_id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':numero_compte_id', $lastNumeroCompteId, PDO::PARAM_INT);
            $updateStmt->bindParam(':user_id', $lastUserId, PDO::PARAM_INT);
            if ($updateStmt->execute()) {
                header("Location: gestion_user.php");
            } else {
                // Handle the update error
            }
        } else {
            // Handle the "numero_compte" insert error
        }
    } else {
        // Handle the "users" insert error
    }
}

$conn = null;
