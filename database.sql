CREATE DATABASE db_esp32 CHARACTER SET = 'utf8' COLLATE = 'utf8_general_ci';
USE db_esp32;
CREATE TABLE vital_signs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sensor_names VARCHAR(30) NOT NULL,
    temperature_value	FLOAT DEFAULT 0.0,
    heart_rate_value	FLOAT DEFAULT 0.0,
    oxygen_saturation_value FLOAT DEFAULT 0.0,
    reading_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
