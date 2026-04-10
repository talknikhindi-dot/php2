<?php
include "config.php";
try {
    if (isset($pdo)) {
        echo "\n? [SUCCESS] Neon DB Connected Successfully!\n";
    }
} catch (Exception $e) {
    echo "\n? [ERROR] Connection Failed: " . $e->getMessage() . "\n";
}
?>
