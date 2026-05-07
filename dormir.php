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
	
	if ($conexion->connect_error){
			echo "error en la conexion: " . $conexion->connect_error;
	}
	else{
		echo "conectado sin error" . "<br>";
	}
	
	$consulta = "SELECT * FROM clientes";
	
	$resultado = $conexion->query($consulta);
	
	$id = $_POST["id"];
	$dormir = "Delete FROM clientes WHERE ID = $id";
	$resultados = $conexion->query($dormir);
	if ($resultados = TRUE){
		echo "usuario eliminado con éxito";
		
	}
?>
