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

    $id_cliente = $_POST['id_cliente'] ?? '';

    if ($id_cliente !== '') {
        $consulta = "SELECT * FROM reservas WHERE Id_cliente = $id_cliente";
    } else {
        $consulta = "SELECT * FROM reservas";
    }

    $resultado = $conexion->query($consulta);
?>

<html>
<head>
    <title>Reservas</title>
    <meta charset="UTF-8">
</head>
<body>
    <div align="center">
        <form method="POST" action="">
            <label>ID Cliente:
                <input type="text" name="id_cliente" value="<?= $id_cliente ?>">
            </label>
            &nbsp;
            <input type="submit" value="Buscar">
            <?php if ($id_cliente !== ''): ?>
                &nbsp;
                <a href="reservas.php">Ver todas</a>
            <?php endif; ?>
        </form>
    </div>
    <h2 align="center">
        <?= $id_cliente !== '' ? "Reservas del cliente $id_cliente" : "Todas las reservas" ?>
    </h2>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table border="1" align="center">
            <tr>
                <?php
                $campos = $resultado->fetch_fields();
                foreach ($campos as $campo) {
                    echo "<th>" . $campo->name . "</th>";
                }
                ?>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($fila as $valor): ?>
                        <td><?= $valor ?? '' ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p align="center">No se encontraron reservas<?= $id_cliente !== '' ? " para el cliente $id_cliente" : "" ?>.</p>
    <?php endif; ?>
</body>
</html>