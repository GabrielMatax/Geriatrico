<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $sector_id = $_GET['id'];

    // Obtener detalles del sector
    $query_sector = "SELECT id, letra FROM sectores WHERE id = $sector_id";
    $result_sector = $conn->query($query_sector);

    if ($result_sector->num_rows === 1) {
        $row_sector = $result_sector->fetch_assoc();
        $letra_sector = $row_sector['letra'];
    } else {
        echo "Sector no encontrado.";
        exit;
    }
}

// Lógica para procesar la eliminación del sector
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_sector'])) {
    $eliminar_sector = "DELETE FROM sectores WHERE id = $sector_id";
    $result_eliminar_sector = $conn->query($eliminar_sector);

    if ($result_eliminar_sector) {
        echo "Sector eliminado con éxito.";
        header('Location: sectores.php');
        exit;
    } else {
        echo "Error al eliminar el sector: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Sector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="admin.php">ATRAS</a>

<h2>Eliminar Sector</h2>

<!-- Mostrar detalles del sector -->
<p><strong>ID del Sector:</strong> <?php echo $sector_id; ?></p>
<p><strong>Letra del Sector:</strong> <?php echo $letra_sector; ?></p>

<!-- Formulario para confirmar la eliminación del sector -->
<form method="post" action="">
    <p>¿Estás seguro de que deseas eliminar este sector?</p>
    <button type="submit" name="eliminar_sector">Eliminar</button>
</form>

</body>
</html>


