<?php

function connexion()
{
    $port = 3306;
    $password = "";
    $username = "root";
    $servername = "localhost";
    $dbname = "geolocalisation";
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}
