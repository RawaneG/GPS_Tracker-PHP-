<?php
include('database.php');
include('helper.php');

$conn = connexion();
session_start();

(!isset($_SESSION['username'])) ? header("Location: index.php") : null;

$rowsPerPage = isset($_GET['rowsPerPage']) ? intval($_GET['rowsPerPage']) : 5;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;

$sql = "SELECT * FROM users LIMIT $offset, $rowsPerPage";

$statement = $conn->query($sql);
$statement ? $result = $statement->fetchAll(PDO::FETCH_ASSOC) : null;
$totalRowsStmt = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalRowsData = $totalRowsStmt->fetch(PDO::FETCH_ASSOC);
$totalRows = $totalRowsData['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

?>


<!doctype html>
<html lang="en">

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
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

        .pagination {
            display: inline-block;
            margin-top: 10px;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <h1><a href="index.html" class="logo">EJITRACE</a></h1>
            <ul class="list-unstyled components mb-5">
                <li>
                    <a href="#"><span class="fa fa-home mr-3"></span>Accueil</a>
                </li>
                <li class="active">
                    <a href="gestion_user.php"><span class="fa fa-user mr-3"></span> Utilisateurs</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-list-alt mr-3"></span>Usine</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-podcast mr-3"></span>Liste capteurs</a>
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

        <div id="content" class="p-4 p-md-5 pt-5">
            <div style="display:flex;justify-content: space-between;">
                <div>
                    <button type="button" onclick="window.location='add_user.php';" class="btn btn-outline-primary" style="margin: 10px;">Ajouter un utilisateur</button>
                </div>
                <div style="margin-top:18px">
                    <label for="rowsPerPageSelect">Lignes par page</label>
                    <select id="rowsPerPageSelect">
                        <option value="10" <?= ($rowsPerPage == 10) ? 'selected' : ''; ?>>10 lignes</option>
                        <option value="50" <?= ($rowsPerPage == 50) ? 'selected' : ''; ?>>50 lignes</option>
                        <option value="100" <?= ($rowsPerPage == 100) ? 'selected' : ''; ?>>100 lignes</option>
                    </select>
                </div>
            </div>
            <table class="table table-striped" id="myTable">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Activation</th>
                    <th scope="col">Nom complet</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date d'Expiration </th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
                <?php
                foreach ($result as $row) {
                ?>
                    <tr>
                        <td>
                            <?= $row['ID_user']; ?>
                        </td>
                        <td><input type="checkbox"></td>
                        <td>
                            <?= $row['Prenom'];
                            echo " ";
                            echo $row['Nom']; ?>
                        </td>
                        <td>
                            <?= $row['Email']; ?>
                        </td>
                        <td>
                            <?= $row['Expiration_date']; ?>
                        </td>
                        <td>
                            <?php switch ($row['Role']) {
                                case 0:
                                    echo "Admin";
                                    break;
                                case 1:
                                    echo "Technicien";
                                    break;
                                case 2:
                                    echo "Client";
                                    break;
                            } ?>
                        </td>
                        <td>
                            <div>
                                <a href="map.php"><button class="btn" style="background:green;"><i class="fa fa-search-plus" style="color: White;"></i></button></a>
                                <button class="btn" style="background: #2f89fc ;"><i class="fa fa-pencil-square-o" style="color: White;"></i></button>
                                <button class="btn" style="background:red;"><i class="fa fa-close" style="color: White;"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div class="pagination">
                <?php
                for ($page = 1; $page <= $totalPages; $page++) {
                    $queryParams = http_build_query(['page' => $page, 'rowsPerPage' => $rowsPerPage]);
                    $activeClass = ($page == $currentPage) ? 'active' : '';
                    echo '<a class="' . $activeClass . '" href="?' . $queryParams . '">' . $page . '</a>';
                }
                ?>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#rowsPerPageSelect').change(function() {
                        const rowsPerPage = $(this).val();
                        const currentPage = 1;
                        window.location.href = '?page=' + currentPage + '&rowsPerPage=' + rowsPerPage;
                    });
                });
            </script>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>