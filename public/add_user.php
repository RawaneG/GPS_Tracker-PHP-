<?php

include('database.php');
$conn = connexion();
session_start();
(!isset($_SESSION['username'])) ?  header("Location: index.php") : null;
$sql = " SELECT * FROM users ORDER BY ID_user ASC ";
$statement = $conn->query($sql);
$statement ? $result = $statement->fetchAll(PDO::FETCH_ASSOC) : null;

$sql2 = "SELECT id FROM numero_compte";
$statement2 = $conn->query($sql2);
$statement2 ? $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC) : null;
$myId = $result2[count($result2) - 1];
?>
<?php
if (isset($_GET['error'])) {
    $error_message = $_GET['error'];
    echo '<script>alert("' . htmlspecialchars($error_message) . '");</script>';
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Ajout Utilisateur</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .navbars {
            left: 250px;
        }

        table,
        table td {
            border: 1px solid #cccccc;
            text-align: center;

        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #cccccc;
            border-collapse: collapse;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
        }

        .type-2 {
            background-color: #fafafa;
            border: 0;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);
            transition: .3s box-shadow;
        }

        .type-2:hover {
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <h1><a href="index.html" class="logo">EJITRACE</a></h1>
            <ul class="list-unstyled components mb-5">
                <div class="custom-menu">
                    <button type="button" id="sidebarCollapse" onclick="NavBar();" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                </div>
                <li>
                    <a href="#"><span class="fa fa-home mr-3"></span>Accueil</a>
                </li>
                <li class="active">
                    <a href="gestion_user.php"><span class="fa fa-user mr-3"></span>Utilisateurs</a>
                </li>
                <li>
                    <a href="gestion_factory.php"><span class="fa fa-list-alt mr-3"></span>Usine</a>
                </li>
                <li>
                    <a href="gestion_usine.php"><span class="fa  fa-podcast mr-3"></span>Liste capteurs</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-bell mr-3"></span>Liste alarmes</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-list mr-3"></span>Liste des clotures</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-paper-plane mr-3"></span>Box recorder</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-wrench mr-3"></span>Maintenance</a>
                </li>
                <li>
                    <a href="deconnexion.php"><span class="fa fa-sign-in mr-3"></span>Déconnexion</a>
                </li>
            </ul>

        </nav>

        <div id="content" class="p-4 p-md-5 pt-5" style="margin-top: 20px;">
            <form name="f0" method="POST" action="add_client.php" type="submit" onSubmit='return validation()'>
                <input type="hidden" id="account_id" value="<?= $myId["id"] ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Nom</label>
                        <input type="text" class="form-control type-2" id="inputNom" name="inputNom" placeholder="Nom" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Prenom</label>
                        <input type="text" class="form-control type-2" id="inputPrenom" name="inputPrenom" placeholder="Prenom" required>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="inputEmail4">Email</label>
                    <input type="email" class="form-control type-2" id="inputEmail" name="inputEmail" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="inputAddress">Telephone</label>
                    <input type="text" class="form-control type-2" id="inputTel" name="inputTel" placeholder="1234 Main St" required>
                </div>
                <div class="form-row flex justify-content-between">
                    <div class="form-row col-md-6">
                        <label for="start">date d'expiration</label>
                        <input class="px-4 type-2" type="date" id="Date" name="Date" max="2100-12-31" pattern="\d{4}-\d{2}-\d{2}" required />
                    </div>
                    <div class="form-row col-md-6">
                        <label for="Numero_compte">Numéro de Compte</label>
                        <input class="px-4 type-2" type="text" id="Numero_compte" name="Numero_compte" />
                    </div>
                </div>
                <div class="form-group" style="margin-Top:10px">
                    <div>
                        <label for="start">Rôle</label>
                    </div>
                    <div class="form-row">
                        <select id="Role" name="Role" class="px-4 type-2" style="height: 34px; " required>
                            <option value="">Choisir Rôle</option>
                            <option value=0>Admin</option>
                            <option value=1>Technicien</option>
                            <option value=2>Client</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" style="margin-Top: 20px;">Ajouter utilisateur</button>
            </form>
        </div>
    </div>

    <script>
        let account_value = "";
        let Nom = document.querySelector('#inputNom');
        let Prenom = document.querySelector('#inputPrenom');
        let Account_Id = document.querySelector("#account_id");
        let Numero_compte = document.querySelector('#Numero_compte');

        Nom.addEventListener('input', () => {
            Nom.value && Prenom.value !== "" ? account_value = Nom.value + "_" + Prenom.value + "_Account_" + (+Account_Id.value + 1) : null;
            Numero_compte.value = account_value;
        })
        Prenom.addEventListener('input', () => {
            Nom.value && Prenom.value !== "" ? account_value = Nom.value + "_" + Prenom.value + "_Account_" + (+Account_Id.value + 1) : null;
            Numero_compte.value = account_value;
        })
        Numero_compte.addEventListener('input', () => {
            Numero_compte.value = account_value;
        })

        function NavBar() {
            document.getElementById("Nav").classList.remove('navbars');
        }

        function validation(e) {
            var date = new window.Date().getTime();
            var Date = new window.Date(document.f0.Date.value).getTime();
            if (date > Date) {
                alert("Date expiration doit être supérieur");
                return false;
            }
        }
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>