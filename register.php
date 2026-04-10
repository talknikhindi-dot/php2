<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["username"];
    $email = $_POST["email"];

    try {
        // Neon SQL Connection using PDO
        $conn = new PDO($sql_url);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO users_data (name, email) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $email]);

        echo "Success: User $name added to Talknik Database!";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
