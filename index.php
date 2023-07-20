<?php
$servername = "localhost";

// REPLACE with your Database name
$dbname = "db_esp32";
// REPLACE with Database user
$username = "root";
// REPLACE with Database user password
$password = "";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $sensor = $location = $value1 = $value2 = $value3 = "";

define('low_temp', 11);
define('high_temp', 19);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $sensor = test_input($_POST["sensor_names"]);
        $value1 = floatval( test_input($_POST["temperature"]) );
        $value2 = floatval( test_input($_POST["heart_rate"]) );
        $value3 = floatval( test_input($_POST["oxygen_saturation"]) );

        notify_critical_values([$value1, $value2, $value3]);

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO Vital_signs (sensor_names, temperature_value, heart_rate_value, oxygen_saturation_value)
        VALUES ('" . $sensor . "', '" . $value1 . "', '" . $value2 . "', '" . $value3 . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function notify_critical_values($vital_signs) : void {
    [$temperature, $heart_rate, $oxygen_saturation] = $vital_signs;
    $to = "vargasmorenoalberto@gmail.com";
    $subject = "ALERTA, signos vitales";
    $headers = "From: webmaster@example.com";
    switch (true) {
        case ($temperature < low_temp):
            $msg = "ALERTA! La temperatura es muy baja, temperatura= {$temperature}";
            mail($to,$subject,$msg,$headers);
            break;
        case ($temperature > high_temp):
            $msg = "ALERTA! La temperatura es muy alta, temperatura= {$temperature}";
            mail($to,$subject,$msg,$headers);
            break;
        default:
            break;
    }
}