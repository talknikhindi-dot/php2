<?php
include "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $email = $_POST["email"];
    $sql = "INSERT INTO users (username, email) VALUES (?, ?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$user, $email]);
    echo "Success: User Registered!";
}
?>
