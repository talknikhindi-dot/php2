<?php
include "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // ???????? ???????

    try {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$username, $email, $password])) {
            echo "<script>alert(\"Signup Successful! Data saved to Neon DB.\"); window.location.href=\"login.php\";</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} ?>
