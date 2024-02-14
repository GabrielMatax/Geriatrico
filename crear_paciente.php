<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'conexion.php';

// Lógica para procesar la creación de paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_paciente'])) {
    $nombre_paciente = $_POST['nombre_paciente'];
    $apellido_paciente = $_POST['apellido_paciente'];
    $sector_id = $_POST['sector_id'];

    // Validar y procesar la creación del paciente
    if (!empty($nombre_paciente) && !empty($apellido_paciente) && !empty($sector_id)) {
        // Insertar el nuevo paciente en la base de datos
        $insertar_paciente = "INSERT INTO pacientes (nombre, apellido, sector_id) VALUES ('$nombre_paciente', '$apellido_paciente', $sector_id)";
        $result_insertar_paciente = $conn->query($insertar_paciente);

        if ($result_insertar_paciente) {     
            echo "Paciente creado con éxito.";
            header('Location: admin.php');
            exit;
        } else {
            echo "Error al crear el paciente: " . $conn->error;
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
    <title>Crear Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="admin.php">ATRAS</a>

<h2>Crear Paciente</h2>

<!-- Formulario para crear paciente -->
<form method="post" action="">
    <label for="nombre_paciente">Nombre del Paciente:</label>
    <input type="text" id="nombre_paciente" name="nombre_paciente" required><br>

    <label for="apellido_paciente">Apellido del Paciente:</label>
    <input type="text" id="apellido_paciente" name="apellido_paciente" required><br>
    <label for="sector_id">Sector:</label>
    <select id="sector_id" name="sector_id" required>
        <?php
        // Obtener la lista de sectores
        $query_sectores = "SELECT id, letra FROM sectores";
        $result_sectores = $conn->query($query_sectores);

        // Mostrar opciones de sectores en el formulario
        while ($row_sector = $result_sectores->fetch_assoc()) {
            echo "<option value='{$row_sector['id']}'>{$row_sector['letra']}</option>";
        }
        ?>
    </select><br>
    <button type="submit" name="crear_paciente">Crear Paciente</button>
</form>

</body>
</html>
