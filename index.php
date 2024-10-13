<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key = $sensor = $value1 = $value2 = $value3 = "";

define('low_temp', 11);
define('high_temp', 19);

if (!($_SERVER["REQUEST_METHOD"] == "POST")) {
    echo "No data posted with HTTP POST.";
    return;
}

$api_key = test_input($_POST["api_key"]);
if($api_key != $api_key_value) {
    echo "Wrong API Key provided.";
    return;
}

$sensor = test_input($_POST["sensor_names"]);
$value1 = floatval( test_input($_POST["temperature"]) );
$value2 = floatval( test_input($_POST["heart_rate"]) );
$value3 = floatval( test_input($_POST["oxygen_saturation"]) );
$dbResult = insert_sensors_readings($sensor, $value1, $value2, $value3);
echo $dbResult;
notify_critical_values([$value1, $value2, $value3]);


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function insert_sensors_readings($sensorName, $temperature, $heartRate, $oxygenSaturation) : string {
    $config = parse_ini_file("config.ini");
    
    $servername = $config['server'];
    $dbname     = $config['database_name'];
    $username   = $config['database_username'];
    $password   = $config['database_password'];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $query = "INSERT INTO Vital_signs (sensor_names, temperature_value, heart_rate_value, oxygen_saturation_value) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        return "Error preparing statement: " . $conn->error;
    }

    $stmt->bind_param("sddd", $sensorName, $temperature, $heartRate, $oxygenSaturation);
    $result = '';
    $result = $stmt->execute() ? "New record created successfully" : "Error: " . $stmt->error;

    $stmt->close();
    $conn->close();

    return $result;
}

function notify_critical_values($vital_signs) : void {
    [$temperature, $heart_rate, $oxygen_saturation] = $vital_signs;
    $to = "vargasmorenoalberto@gmail.com";
    $subject = "ALERTA, signos vitales";
    $from = "webmaster@example.com";
    switch (true) {
        case ($temperature < low_temp):
            $msg = "ALERTA! La temperatura es muy baja, temperatura= {$temperature}";
            sendEmail($to,$subject,$msg,$from);
            break;
        case ($temperature > high_temp):
            $msg = "ALERTA! La temperatura es muy alta, temperatura= {$temperature}";
            sendEmail($to,$subject,$msg,$from);
            break;
        default:
            break;
    }
}

function sendEmail($to, $subject, $message, $from) {
    $mail = new PHPMailer(true);
    $config = parse_ini_file("config.ini");
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host         = $config['mail_host'];
        $mail->SMTPAuth     = true;
        $mail->Username     = $config['mail_username'];
        $mail->Password     = $config['mail_password'];
        $mail->SMTPSecure   = $config['mail_encryption'];
        $mail->Port         = $config['mail_port'];

        //Recipients
        $mail->setFrom($from, 'Mailer');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = $message;

        $succesBool = $mail->send();
        return $succesBool;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }   
}