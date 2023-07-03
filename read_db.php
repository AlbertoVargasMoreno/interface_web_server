<!DOCTYPE html>
<html>
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

$rows = [];
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
    }
    $result->free();
}
$conn->close();
?>

<body>
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td>ID</td>
            <td>Sensor</td>
            <td>Temperatura</td>
            <td>Ritmo Cardiaco</td>
            <td>Saturacion de Oxigeno</td>
            <td>Marca de Tiempo</td>
        </tr>
        <?php foreach ($rows as $index => $result_row): ?>
            <tr>
                <td>
                    <?php echo $result_row['id']; ?>
                </td>
                <td>
                    <?php echo $result_row['sensor_names']; ?>
                </td>
                <td>
                    <?php echo $result_row['temperature_value']; ?>
                </td>
                <td>
                    <?php echo $result_row['heart_rate_value']; ?>
                </td>
                <td>
                    <?php echo $result_row['oxygen_saturation_value']; ?>
                </td>
                <td>
                    <?php echo $result_row['reading_time']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
