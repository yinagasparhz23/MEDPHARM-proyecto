<?php
include 'conexion.php';
$datos = json_decode(file_get_contents('php://input'), true);
$nombre = $datos['nombre'] ?? '';
$contacto = $datos['contacto'] ?? '';
$telefono = $datos['telefono'] ?? '';
$email = $datos['email'] ?? '';

$sql = "INSERT INTO proveedor (NOMBRE, CONTACTO, TELEFONO, EMAIL) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $nombre, $contacto, $telefono, $email);
mysqli_stmt_execute($stmt);

echo json_encode(['ok' => true]);
mysqli_close($conexion);
?>