<?php
include 'conexion.php';
$datos = json_decode(file_get_contents('php://input'), true);
$nombre = $datos['nombre'] ?? '';
$categoria = $datos['categoria'] ?? '';
$lote = $datos['lote'] ?? '';
$stock = intval($datos['stock'] ?? 0);
$fecha = $datos['fecha'] ?? '';
$proveedor_nombre = $datos['proveedor'] ?? '';

$prov_id = null;
if ($proveedor_nombre) {
    $sql_p = "SELECT id FROM proveedor WHERE NOMBRE = ? LIMIT 1";
    $stmt_p = mysqli_prepare($conexion, $sql_p);
    mysqli_stmt_bind_param($stmt_p, "s", $proveedor_nombre);
    mysqli_stmt_execute($stmt_p);
    $res_p = mysqli_stmt_get_result($stmt_p);
    if (mysqli_num_rows($res_p) > 0) {
        $fila = mysqli_fetch_assoc($res_p);
        $prov_id = $fila['id'];
    } else {
        $sql_ip = "INSERT INTO proveedor (NOMBRE) VALUES (?)";
        $stmt_ip = mysqli_prepare($conexion, $sql_ip);
        mysqli_stmt_bind_param($stmt_ip, "s", $proveedor_nombre);
        mysqli_stmt_execute($stmt_ip);
        $prov_id = mysqli_insert_id($conexion);
    }
}

$sql_prod = "INSERT INTO producto (NOMBRE, CATEGORIA, STOCK, STOCK_MINIMO, proveedor_id) VALUES (?, ?, ?, 20, ?)";
$stmt_prod = mysqli_prepare($conexion, $sql_prod);
mysqli_stmt_bind_param($stmt_prod, "sssi", $nombre, $categoria, $stock, $prov_id);
mysqli_stmt_execute($stmt_prod);
$prod_id = mysqli_insert_id($conexion);

if ($lote && $fecha) {
    $sql_lote = "INSERT INTO lote (LOTE, FECHA_VENCIMIENTO, producto_id) VALUES (?, ?, ?)";
    $stmt_lote = mysqli_prepare($conexion, $sql_lote);
    mysqli_stmt_bind_param($stmt_lote, "ssi", $lote, $fecha, $prod_id);
    mysqli_stmt_execute($stmt_lote);
}

echo json_encode(['ok' => true]);
mysqli_close($conexion);
?>