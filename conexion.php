<?php
//Diego García González y Eduardo Álvarez Alonso
error_reporting(0);
	include "usuario.php";
	include "libro.php";
	include "peliculas.php";
	
	
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
		
?>
