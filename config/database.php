<?php

$host = 'localhost';
$name = 'root';
$password = '';
$database = 'maintenance_db';

$conn = mysqli_connect($host, $name, $password, $database);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');