<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_emploi_mgsi";

try {
    $PDO = new PDO("mysql:host=$localhost;dbname=$dbname;charset=utf8mb4", $username, $password);

    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());  
}

?>

