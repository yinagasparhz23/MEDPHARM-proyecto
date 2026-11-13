<?php
include 'conexion.php';

$sql = "SELECT id, NOMBRE AS nombre, CONTACTO AS contacto, TELEFONO AS telefono, EMAIL AS email 
        FROM proveedor";
$resultado = mysqli_query($conexion, $sql);
$proveedores = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $proveedores[] = $fila;
}

echo json_encode($proveedores);
mysqli_close($conexion);
?>