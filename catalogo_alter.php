<?php
// Diego García González y Eduardo Álvarez Alonso
    session_start();
  	
    if (!isset($_SESSION['usuario'])) {
        header("Location: loginpoo.html");
        exit();
    }

    include "Objects/usuario.php";
    include "Objects/libro.php";
    include "Objects/peliculas.php";

    $servidor = "bbdd";
    $usuario = "root";
    $contraseña = "bbdd";
    $nombre_bbdd = "bbdd_biblioteca";

    $conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);
    $conexion->set_charset("utf8mb4");

    if ($conexion->connect_error) {
        echo "Error en la conexión: " . $conexion->connect_error;
        exit();
    } else {
        echo "Conectado sin error<br>";
    }

    $mostrar_libros    = isset($_POST['mostrar_libros']);
    $mostrar_peliculas = isset($_POST['mostrar_peliculas']);
    $titulo   = $_POST['titulo']   ?? '';
    $genero   = $_POST['genero']   ?? '';
    $director = $_POST['director'] ?? '';
    $anio     = $_POST['anio']     ?? '';

    $resultado_libros    = null;
    $resultado_peliculas = null;

    if ($mostrar_libros) {
        $cond_libros = "WHERE 1=1";
        if ($titulo   !== '') $cond_libros .= " AND l.titulo LIKE '%" . $conexion->real_escape_string($titulo)   . "%'";
        if ($genero   !== '') $cond_libros .= " AND l.genero LIKE '%" . $conexion->real_escape_string($genero)   . "%'";
        if ($director !== '') $cond_libros .= " AND a.Autor LIKE '%" . $conexion->real_escape_string($director) . "%'";
        if ($anio     !== '') $cond_libros .= " AND YEAR(l.`Año`) LIKE '%" . $conexion->real_escape_string($anio) . "%'";

        $consulta_libros = "
            SELECT l.Id, l.titulo, a.Autor AS autor, l.genero, l.`Año`,
                   CASE WHEN r.Id IS NOT NULL THEN 'Reservado' ELSE 'Disponible' END AS estado
            FROM libros l
            LEFT JOIN autores a ON l.Autor_id = a.ID
            LEFT JOIN reservas r ON r.Id_libro = l.Id
            $cond_libros
        ";      
		$resultado_libros = $conexion->query($consulta_libros);
    }

    if ($mostrar_peliculas) {
        $cond_peliculas = "WHERE 1=1";
        if ($titulo   !== '') $cond_peliculas .= " AND titulo   LIKE '%" . $conexion->real_escape_string($titulo)   . "%'";
        if ($genero   !== '') $cond_peliculas .= " AND genero   LIKE '%" . $conexion->real_escape_string($genero)   . "%'";
        if ($director !== '') $cond_peliculas .= " AND director LIKE '%" . $conexion->real_escape_string($director) . "%'";
        if ($anio     !== '') $cond_peliculas .= " AND YEAR(`Año_estreno`) LIKE '%" . $conexion->real_escape_string($anio)  . "%'";

        $consulta_peliculas  = "SELECT * FROM peliculas $cond_peliculas";
        $resultado_peliculas = $conexion->query($consulta_peliculas);
    }
?>

<html>
<head>
    <title>Biblioteca</title>
    <meta charset="UTF-8">
</head>
<body>

    <div align="center">
        <form method="POST" action="">

            <label>
                <input type="checkbox" name="mostrar_libros" value="1"
                    style="accent-color: blue;"
                    <?= $mostrar_libros || (!isset($_POST['mostrar_libros']) && !isset($_POST['mostrar_peliculas'])) ? 'checked' : '' ?>>
                Libros
            </label>

            &nbsp;&nbsp;

            <label>
                <input type="checkbox" name="mostrar_peliculas" value="1"
                    style="accent-color: blue;"
                    <?= $mostrar_peliculas || (!isset($_POST['mostrar_libros']) && !isset($_POST['mostrar_peliculas'])) ? 'checked' : '' ?>>
                Películas
            </label>

            <br><br>

            <label>Título:
                <input type="text" name="titulo" value="<?= htmlspecialchars($titulo) ?>">
            </label>
            &nbsp;
            <label>Género:
                <input type="text" name="genero" value="<?= htmlspecialchars($genero) ?>">
            </label>
            &nbsp;
            <label>Director/Autor:
                <input type="text" name="director" value="<?= htmlspecialchars($director) ?>">
            </label>
            &nbsp;
            <label>Año:
                <input type="text" name="anio" value="<?= htmlspecialchars($anio) ?>">
            </label>

            <br><br>
            <input type="submit" value="Filtrar">

        </form>
    </div>

    <?php if ($mostrar_libros || (!isset($_POST['mostrar_libros']) && !isset($_POST['mostrar_peliculas']))): ?>
        <h2>Libros</h2>
        <?php if ($resultado_libros && $resultado_libros->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <?php
                    $campos = $resultado_libros->fetch_fields();
                    foreach ($campos as $campo) {
                        echo "<th>" . htmlspecialchars($campo->name) . "</th>";
                    }
                    ?>
                </tr>
                <?php while ($fila = $resultado_libros->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                            <td><?= htmlspecialchars($valor ?? '') ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay libros que coincidan con los filtros.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($mostrar_peliculas || (!isset($_POST['mostrar_libros']) && !isset($_POST['mostrar_peliculas']))): ?>
        <h2>Películas</h2>
        <?php if ($resultado_peliculas && $resultado_peliculas->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <?php
                    $campos = $resultado_peliculas->fetch_fields();
                    foreach ($campos as $campo) {
                        echo "<th>" . htmlspecialchars($campo->name) . "</th>";
                    }
                    ?>
                </tr>
                <?php while ($fila = $resultado_peliculas->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                            <td><?= htmlspecialchars($valor ?? '') ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay películas que coincidan con los filtros.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>