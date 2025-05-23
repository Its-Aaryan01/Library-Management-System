<?php
$host = 'localhost';
$user = 'root'; // default in XAMPP
$pass = '';     // empty if not set
$dbname = 'library_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
