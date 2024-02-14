<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'conexion.php';

// Lógica para procesar la creación de un nuevo medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_medicamento'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Validar y procesar la creación del medicamento
    if (!empty($nombre) && !empty($cantidad) && !empty($fecha_vencimiento)) {
        // Insertar el nuevo medicamento en la base de datos
        $insertar_medicamento = "INSERT INTO medicamentos (nombre, cantidad, fecha_vencimiento)
                                VALUES ('$nombre', $cantidad, '$fecha_vencimiento')";
        $result_insertar_medicamento = $conn->query($insertar_medicamento);

        if ($result_insertar_medicamento) {
            echo "Medicamento creado con éxito.";
        } else {
            echo "Error al crear el medicamento: " . $conn->error;
        }
    } else {
        echo "Complete todos los campos del medicamento.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Medicamento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Crear Medicamento</h2>

<!-- Formulario para crear un nuevo medicamento -->
<form method="post" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" required><br>

    <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required><br>

    <button type="submit" name="crear_medicamento">Crear Medicamento</button>
</form>

</body>
</html>
