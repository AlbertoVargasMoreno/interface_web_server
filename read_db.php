<?php
function read_vital_signs_history(): array  {
    $config = parse_ini_file("config.ini");
    
    $servername = $config['server'];
    $dbname     = $config['database_name'];
    $username   = $config['database_username'];
    $password   = $config['database_password'];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM vital_signs";

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
    $temperature = json_encode(array_reverse(array_column($rows, 'temperature_value')), JSON_NUMERIC_CHECK);
    $heart_rate = json_encode(array_reverse(array_column($rows, 'heart_rate_value')), JSON_NUMERIC_CHECK);
    $reading_time = json_encode(array_reverse(array_column($rows, 'reading_time')), JSON_NUMERIC_CHECK);
    return [
        'temperature'   => $temperature,
        'heart_rate'    => $heart_rate,
        'reading_time'  => $reading_time,
        'rows'          => $rows
    ];
}

$result = read_vital_signs_history();
echo json_encode($result);