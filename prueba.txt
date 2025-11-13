<?php
require_once 'database.php'; // Asegúrate que el nombre coincida con tu archivo real

try {
    $conexion = getConnection();
    echo "<h2 style='color: green;'>✅ Conexión exitosa a la base de datos.</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
