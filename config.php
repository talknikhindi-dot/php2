<?php
$host = "YOUR_NEON_ENDPOINT"; // Neon Dashboard ???? ????
$db   = "neondb";
$user = "YOUR_USER";
$pass = "YOUR_PASSWORD";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db;sslmode=require", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect. " . $e->getMessage());
}
?>
