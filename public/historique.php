<?php
require_once "./database.php";

$conn = connexion();

$vehicle_name = $_GET['name'] ?? '';
$vehicle_name = strtolower($vehicle_name);

$sql = "SELECT * FROM `gs_object_data_$vehicle_name`";

$statement = $conn->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();

if (count($result) > 0) {
    foreach ($result as $row) {
        $output[] =
            [
                'angle' => $row['angle'],
                'speed' => $row['speed'],
                'latitude' => $row['lat'],
                'longitude' => $row['lng'],
                'date' => $row['dt_tracker'],
                'altitude' => $row['altitude'],
            ];
    }
    echo json_encode($output);
}
