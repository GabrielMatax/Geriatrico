<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'conexion.php';

// Obtener el ID del paciente desde la URL
$paciente_id = $_GET['id'];

// Obtener información del paciente
$query_paciente = "SELECT id, nombre, apellido, sector_id FROM pacientes WHERE id = $paciente_id";
$result_paciente = $conn->query($query_paciente);

// Verificar si se encontró al paciente
if ($result_paciente->num_rows === 1) {
    $row_paciente = $result_paciente->fetch_assoc();
    $nombre = $row_paciente['nombre'];
    $apellido = $row_paciente['apellido'];
    $sector_id = $row_paciente['sector_id'];
} else {
    // Manejar el caso en que no se encuentre al paciente
    echo "Paciente no encontrado.";
    exit;
}

// Lógica para procesar la modificación del paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_paciente'])) {
    $nombre_paciente = $_POST['nombre_paciente'];
    $apellido_paciente = $_POST['apellido_paciente'];
    $sector_id = $_POST['sector_id'];

    // Validar y procesar la modificación del paciente
    if (!empty($nombre_paciente) && !empty($apellido_paciente) && !empty($sector_id)) {
        // Actualizar la información del paciente en la base de datos
        $actualizar_paciente = "UPDATE pacientes SET nombre = '$nombre_paciente', apellido = '$apellido_paciente', sector_id = $sector_id WHERE id = $paciente_id";
        $result_actualizar_paciente = $conn->query($actualizar_paciente);

        if ($result_actualizar_paciente) {
            echo "Paciente modificado con éxito.";
        } else {
            echo "Error al modificar el paciente: " . $conn->error;
        }
    } else {
        echo "Complete todos los campos del paciente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="admin.php">ATRAS</a>

<h2>Editar Paciente</h2>
<!-- Formulario para editar paciente -->
<form method="post" action="">
    <label for="nombre_paciente">Nombre del Paciente:</label>
    <input type="text" id="nombre_paciente" name="nombre_paciente" value="<?php echo $nombre; ?>" required><br>
    <label for="apellido_paciente">Apellido del Paciente:</label>
    <input type="text" id="apellido_paciente" name="apellido_paciente" value="<?php echo $apellido; ?>" required><br>

    <label for="sector_id">Sector:</label>
    <select id="sector_id" name="sector_id" required>
        <?php
        // Obtener la lista de sectores
        $query_sectores = "SELECT id, letra FROM sectores";
        $result_sectores = $conn->query($query_sectores);

        // Mostrar opciones de sectores en el formulario
        while ($row_sector = $result_sectores->fetch_assoc()) {
            $selected = ($row_sector['id'] == $sector_id) ? "selected" : "";
            echo "<option value='{$row_sector['id']}' $selected>{$row_sector['letra']}</option>";
        }
        ?>
    </select><br>

    <button type="submit" name="editar_paciente">Guardar Cambios</button>
</form>

</body>
</html>
