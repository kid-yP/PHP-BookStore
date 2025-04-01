<?php 
$host = 'localhost'; 
$db = 'bookstore'; 
$user = 'root'; // change if necessary 
$pass = ''; // change if necessary 
try { 
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) { 
    echo "Connection failed: " . $e->getMessage(); 
} 
?>