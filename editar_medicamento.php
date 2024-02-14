<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'conexion.php';

// Obtener el ID del medicamento desde la URL
$medicamento_id = $_GET['id'];

// Lógica para procesar la edición del medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_medicamento'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Validar y procesar la edición del medicamento
    if (!empty($nombre) && !empty($cantidad) && !empty($fecha_vencimiento)) {
        // Actualizar la información del medicamento en la base de datos
        $editar_medicamento = "UPDATE medicamentos
                               SET nombre = '$nombre', cantidad = $cantidad, fecha_vencimiento = '$fecha_vencimiento'
                               WHERE id = $medicamento_id";
        $result_editar_medicamento = $conn->query($editar_medicamento);

        if ($result_editar_medicamento) {
            echo "Medicamento editado con éxito.";
        } else {
            echo "Error al editar el medicamento: " . $conn->error;
        }
    } else {
        echo "Complete todos los campos del medicamento.";
    }
}

// Obtener información actual del medicamento
$query_medicamento = "SELECT id, nombre, cantidad, fecha_vencimiento FROM medicamentos WHERE id = $medicamento_id";
$result_medicamento = $conn->query($query_medicamento);

// Verificar si se encontró el medicamento
if ($result_medicamento->num_rows === 1) {
    $row_medicamento = $result_medicamento->fetch_assoc();
    $nombre = $row_medicamento['nombre'];
    $cantidad = $row_medicamento['cantidad'];
    $fecha_vencimiento = $row_medicamento['fecha_vencimiento'];
} else {
    // Manejar el caso en que no se encuentre el medicamento
    echo "Medicamento no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Editar Medicamento</h2>

<!-- Formulario para editar un medicamento existente -->
<form method="post" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>" required><br>

    <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $fecha_vencimiento; ?>" required><br>

    <button type="submit" name="editar_medicamento">Guardar Cambios</button>
</form>

</body>
</html>
