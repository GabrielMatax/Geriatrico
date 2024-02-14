<?php
// Inicia la sesión si aún no se ha iniciado
session_start();

// Incluye tu archivo de conexión a la base de datos
include 'conexion.php';

// Obtiene la lista de enfermeros desde la base de datos
$query_enfermeros = "SELECT id, nombre, apellido FROM enfermeros";
$result_enfermeros = $conn->query($query_enfermeros);

// Verifica si se ha seleccionado un enfermero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enfermero_id'])) {
    // Obtiene el ID del enfermero seleccionado
    $enfermero_id = $_POST['enfermero_id'];

    // Almacena el ID del enfermero en la sesión
    $_SESSION['usuario_id'] = $enfermero_id;

    // Redirige al área del enfermero
    header('Location: enfermero.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Selección de Enfermero</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Seleccione un Enfermero</h2>

<form method="post" action="">
    <label for="enfermero_id">Enfermero:</label>
    <select id="enfermero_id" name="enfermero_id">
        <?php while ($row_enfermero = $result_enfermeros->fetch_assoc()) : ?>
            <option value="<?php echo $row_enfermero['id']; ?>"><?php echo $row_enfermero['nombre'] . ' ' . $row_enfermero['apellido']; ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Seleccionar</button>
</form>

</body>
</html>
