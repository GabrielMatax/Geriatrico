<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location:index.php');
    exit;
}

include 'conexion.php';

// Obtener el ID del medicamento desde la URL
$medicamento_id = $_GET['id'];

// Obtener información del medicamento
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
    <title>Detalles de Medicamento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Detalles de Medicamento</h2>

<p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
<p><strong>Cantidad:</strong> <?php echo $cantidad; ?></p>
<p><strong>Fecha de Vencimiento:</strong> <?php echo $fecha_vencimiento; ?></p>


</body>
</html>

