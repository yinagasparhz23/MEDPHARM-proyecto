<?php
$servidor = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$base_datos = "inventariomedpharm";

try {
    $pdo = new PDO(
        "mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $contrasena
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado correctamente";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>