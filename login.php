<?php
//Diego García González y Eduardo Álvarez Alonso
session_start();
include "Objects/usuario.php";
$servidor    = "bbdd";
$usuario     = "root";
$contraseña  = "bbdd";
$nombre_bbdd = "bbdd_biblioteca";
$conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);
if ($conexion->connect_error){
    die("Error en la conexión: " . $conexion->connect_error);
}
$usuarioLogin    = $_POST["usuario"]   ?? '';
$contraseñaLogin = hash("sha256", $_POST["contraseña"] ?? '');
if ($usuarioLogin != "" && $contraseñaLogin != "") {
    $consulta  = "SELECT * FROM usuarios WHERE usuario = '$usuarioLogin' AND contrasena = '$contraseñaLogin'";
    $resultado = $conexion->query($consulta);
    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuarioLogin;
        header("Location: index.html");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
} else {
    echo "Datos no válidos.";
}
?>