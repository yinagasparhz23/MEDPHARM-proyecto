<?php
include 'conexion.php';
$id = intval($_GET['id'] ?? 0);
mysqli_query($conexion, "DELETE FROM lote WHERE producto_id = $id");
mysqli_query($conexion, "DELETE FROM producto WHERE id = $id");
echo json_encode(['ok' => true]);
mysqli_close($conexion);
?>