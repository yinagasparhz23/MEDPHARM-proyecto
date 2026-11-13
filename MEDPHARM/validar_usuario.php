<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'conexion.php';

echo "<h3>🔍 DIAGNÓSTICO</h3>";

// Verificar si llegan los datos
if (!isset($_POST['usuario'], $_POST['codigo'])) {
    echo "<p>❌ No llegaron los datos del formulario</p>";
    echo "<p><a href='login.html'>Volver al login</a></p>";
    exit;
}

$nombre = trim($_POST['usuario']);
$clave = trim($_POST['codigo']);

echo "<p>Usuario recibido: <b>$nombre</b></p>";
echo "<p>Clave recibida: <b>$clave</b></p>";

// Consulta con los nombres CORRECTOS de tu tabla
$consulta = "SELECT * FROM usuario WHERE NOMBRE = ? AND CONTRASEÑA = ?";
$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "ss", $nombre, $clave);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    echo "<p>✅ ¡USUARIO ENCONTRADO!</p>";
    $fila = mysqli_fetch_assoc($resultado);
    $_SESSION['usuario'] = $fila['NOMBRE'];
    $_SESSION['id_usuario'] = $fila['id_usuario'];
    echo "<p>Redirigiendo al panel...</p>";
    header("Refresh: 2; url=panel.html");
    exit;
} else {
    echo "<p>❌ Usuario o clave incorrectos</p>";
    echo "<p><a href='login.html'>Volver a intentar</a></p>";
}
?>