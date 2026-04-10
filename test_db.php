<?php
include "config.php";
try {
    if (isset($pdo)) {
        echo "? [SUCCESS] Neon SQL Database Connected Successfully!\n";
        $stmt = $pdo->query("SELECT current_database(), now()");
        $row = $stmt->fetch();
        echo "Connected to: " . $row["current_database"] . "\n";
        echo "Server Time: " . $row["now"] . "\n";
    }
} catch (Exception $e) {
    echo "? [ERROR] Connection Failed: " . $e->getMessage() . "\n";
}
?>
