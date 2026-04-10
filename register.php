<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $email = $_POST["email"];
    // ??????? ???????? ??????????? ??? ????
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user, $email, $pass]);
        echo "Success: User Registered!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23505) {
            echo "Error: Email already exists!";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
