CREATE DATABASE IF NOT EXISTS taquilla_parque;
USE taquilla_parque;

CREATE TABLE attractions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    maintenance TINYINT(1) DEFAULT 0,
    duration_minutes INT,
    min_height_cm INT,
    category VARCHAR(50)
);
