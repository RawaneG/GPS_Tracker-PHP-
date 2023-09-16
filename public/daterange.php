<?php
require_once "./database.php";

$conn = connexion();

$end_date = $_GET['endDate'] ?? '';
$vehicle_name = $_GET['name'] ?? '';
$start_date = $_GET['startDate'] ?? '';
$vehicle_name = strtolower($vehicle_name);
$selectedStartDate = date("Y-m-d H:i:s", strtotime($start_date));
$selectedEndDate = date("Y-m-d H:i:s", strtotime($end_date));

$sql = "SELECT * FROM `gs_object_data_$vehicle_name` WHERE `dt_tracker` BETWEEN '$selectedStartDate' AND '$selectedEndDate'";
$statement = $conn->prepare($sql);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

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
