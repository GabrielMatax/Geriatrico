<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "geriatricobd";

$conn = mysqli_connect($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>