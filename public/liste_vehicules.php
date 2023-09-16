<?php
require_once('database.php');
session_start();

$conn = connexion();
$searchText = $_GET['searchText'] ?? '';
$searchText = strtolower($searchText);

$sql = "SELECT * FROM gs_objects g WHERE LOWER(name) LIKE '%$searchText%' AND g.Numero_compte = {$_SESSION['numero_compte']}";

$statement = $conn->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();

if (count($result) > 0) {
    foreach ($result as $row) {
        $output[] =
            [
                'name' => $row['name'],
                'icon' => $row['icon'],
                'speed' => $row['speed'],
                'date' => $row['dt_tracker'],
                'tail_color' => $row['tail_color'],
                'tail_points' => $row['tail_points']
            ];
    }
    echo json_encode($output);
} else {
    $output = [];
    echo json_encode($output);
}
