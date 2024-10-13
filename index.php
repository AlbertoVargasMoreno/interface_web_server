<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$config = parse_ini_file("config.ini");
$servername = $config['server'];
$dbname     = $config['database_name'];
$username   = $config['database_username'];
$password   = $config['database_password'];

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
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = 'This is the HTML message body <b>in bold!</b> ' . $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $succesBool = $mail->send();
        return $succesBool;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }   
}