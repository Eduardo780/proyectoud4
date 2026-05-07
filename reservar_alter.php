<?php
// Diego García González y Eduardo Álvarez Alonso
    include "Objects/libro.php";
    include "Objects/peliculas.php";
    $servidor    = "bbdd";
    $usuario     = "root";
    $contraseña  = "bbdd";
    $nombre_bbdd = "bbdd_biblioteca";
    $conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);
    $conexion->set_charset("utf8mb4");
    if ($conexion->connect_error) {
        echo "Error en la conexión: " . $conexion->connect_error;
        exit();
    }
    $id_cliente  = $_POST['id_cliente']  ?? '';
    $id_libro    = $_POST['id_libro']    ?? '';
    $id_pelicula = $_POST['id_pelicula'] ?? '';
    if (isset($_POST['reservar']) && $id_cliente !== '') {
        if ($id_libro !== '') {

            $check = $conexion->query("SELECT Id FROM reservas WHERE Id_libro = $id_libro");
            if ($check->num_rows > 0) {
                echo "<p><b>No se puede reservar este libro porque ya está reservado.</b></p>";
            } else {
                $result   = $conexion->query("SELECT MAX(Id) as max_id FROM reservas");
                $row      = $result->fetch_assoc();
                $nuevo_id = ($row['max_id'] ?? 0) + 1;
                $conexion->query("INSERT INTO reservas (Id, Id_cliente, Id_libro, Fecha_reserva) VALUES ($nuevo_id, $id_cliente, $id_libro, CURDATE())");
                $conexion->query("UPDATE libros SET reservado = 1 WHERE Id = $id_libro");
                echo "<p><b>Reserva de libro realizada con éxito.</b></p>";
            }
        }
        if ($id_pelicula !== '') {
            $check = $conexion->query("SELECT Id FROM reservas WHERE Id_pelicula = $id_pelicula");
            if ($check->num_rows > 0) {
                echo "<p><b>No se puede reservar esta película porque ya está reservada.</b></p>";
            } else {
                $result   = $conexion->query("SELECT MAX(Id) as max_id FROM reservas");
                $row      = $result->fetch_assoc();
                $nuevo_id = ($row['max_id'] ?? 0) + 1;
                $conexion->query("INSERT INTO reservas (Id, Id_cliente, Id_pelicula, Fecha_reserva) VALUES ($nuevo_id, $id_cliente, $id_pelicula, CURDATE())");
                $conexion->query("UPDATE peliculas SET reservado = 1 WHERE Id = $id_pelicula");
                echo "<p><b>Reserva de película realizada con éxito.</b></p>";
            }
        }
    }
?>
<html>
<head>
    <title>Nueva Reserva</title>
    <meta charset="UTF-8">
</head>
<body>
    <div align="center">
        <h2>Nueva Reserva</h2>
        <form method="POST" action="">
            <label>ID Cliente:
                <input type="text" name="id_cliente" value="<?= htmlspecialchars($id_cliente) ?>">
            </label>
            <br><br>
            <label>ID Libro:
                <input type="text" name="id_libro" value="<?= htmlspecialchars($id_libro) ?>">
            </label>
            <br><br>
            <label>ID Película:
                <input type="text" name="id_pelicula" value="<?= htmlspecialchars($id_pelicula) ?>">
            </label>
            <br><br>
            <input type="submit" name="reservar" value="Reservar">
        </form>
    </div>
</body>
</html>