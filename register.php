<?php
include "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $email = $_POST["email"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$user, $email, $pass]);
        echo "<h2 style=\"color: #00ffcc; text-align: center; margin-top: 50px;\">Success: Account Created in Neon DB!</h2>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} ?>
