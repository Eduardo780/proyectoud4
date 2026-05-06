<?php
// Diego García González y Eduardo Álvarez Alonso
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header("Location: loginpoo.html");
        exit();
    }
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
    if (isset($_POST['cancelar']) && $id_cliente !== '') {
        if ($id_libro !== '') {
            $check = $conexion->query("SELECT * FROM reservas WHERE Id_cliente = $id_cliente AND Id_libro = $id_libro");
            if ($check->num_rows === 0) {
                echo "<p><b>No existe ninguna reserva de este libro para este cliente.</b></p>";
            } else {
                $conexion->query("UPDATE libros SET reservado = 0 WHERE Id = $id_libro");
                echo "<p><b>Reserva de libro cancelada con éxito.</b></p>";
            }
        }
        if ($id_pelicula !== '') {
            $check = $conexion->query("SELECT * FROM reservas WHERE Id_cliente = $id_cliente AND id_pelicula = $id_pelicula");
            if ($check->num_rows === 0) {
                echo "<p><b>No existe ninguna reserva de esta película para este cliente.</b></p>";
            } else {
                $conexion->query("UPDATE peliculas SET reservado = 0 WHERE Id = $id_pelicula");
                echo "<p><b>Reserva de película cancelada con éxito.</b></p>";
            }
        }
    }
?>
<html>
<head>
    <title>Cancelar Reserva</title>
    <meta charset="UTF-8">
</head>
<body>
    <div align="center">
        <h2>Cancelar Reserva</h2>
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
            <input type="submit" name="cancelar" value="Cancelar reserva">
        </form>
    </div>
</body>
</html>