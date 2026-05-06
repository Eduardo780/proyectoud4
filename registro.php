<?php
//Diego García González y Eduardo Álvarez Alonso
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: loginpoo.html");
        exit();
    }
	
	
	include "Objects/usuario.php";
	//class mysqli
	
	$servidor = "bbdd"; //nombre base de datos compose
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
	
	$consulta = "SELECT * FROM usuarios";
	
	$resultado = $conexion->query($consulta);
	
	$nuevoUsuario = $_POST["usuario"];
	$nuevaContraseña = hash("sha256", $_POST["contraseña"]);
	if($nuevoUsuario != "" and $nuevaContraseña != ""){
		$adicion = "INSERT into usuarios (usuario, contrasena) VALUES ('$nuevoUsuario', 'nuevaContraseña')";
		$resultados = $conexion->query($adicion);
		
		if($resultados == true){
			echo "bien hecho";
		}
		else{
			echo"vas a repetir";
		}
	}
	else{
		echo "datos no validos";
	}
?>