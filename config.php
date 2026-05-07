<?php
session_start();

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "if0_41819712_gestionedt";

try {
    $PDO = new PDO("mysql:host=$localhost;dbname=$dbname;charset=utf8mb4", $username, $password);

    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
