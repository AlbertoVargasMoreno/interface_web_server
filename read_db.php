<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "db_esp32";
// REPLACE with Database user
$username = "root";
// REPLACE with Database user password
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM Vital_signs";

$resultado = $conn->query($sql);
$fila = $resultado->fetch_assoc();
var_dump(
    $fila
);
