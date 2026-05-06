<?php
//Diego García González y Eduardo Álvarez Alonso
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: loginpoo.html");
    exit();
}

include "Objects/usuario.php";

$servidor = "bbdd";
$usuario = "root";
$contraseña = "bbdd";
$nombre_bbdd = "bbdd_biblioteca";

$conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $resultado = $conexion->query("SELECT * FROM clientes WHERE Id = $id");
    $cliente = $resultado->fetch_assoc();

    if ($cliente) {
        $nombre    = (!empty($_POST["nombre"]))           ? $_POST["nombre"]           : $cliente["Nombre"];
        $apellidos = (!empty($_POST["apellidos"]))        ? $_POST["apellidos"]        : $cliente["Apellidos"];
        $fecha     = (!empty($_POST["fecha_nacimiento"])) ? $_POST["fecha_nacimiento"] : $cliente["Fecha_nacimiento"];
        $localidad = (!empty($_POST["localidad"]))        ? $_POST["localidad"]        : $cliente["Localidad"];

        $resultados = $conexion->query("UPDATE clientes SET Nombre = '$nombre', Apellidos = '$apellidos', Fecha_nacimiento = '$fecha', Localidad = '$localidad' WHERE Id = $id");
        if ($resultados == TRUE) {
            echo "Usuario actualizado con éxito<br>";
        }
    } else {
        echo "No se encontró el cliente<br>";
    }
}
?>