<?php
include 'conexion.php';
$id = intval($_GET['id'] ?? 0);
mysqli_query($conexion, "DELETE FROM proveedor WHERE id = $id");
echo json_encode(['ok' => true]);
mysqli_close($conexion);
?>