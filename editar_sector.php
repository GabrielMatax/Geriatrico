<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_sector'])) {
    $sector_id = $_POST['sector_id'];
    $nueva_letra = $_POST['nueva_letra'];

    // Actualizar la letra del sector.
    $actualizar_sector = "UPDATE sectores SET letra = '$nueva_letra' WHERE id = $sector_id";
    $result_actualizar_sector = $conn->query($actualizar_sector);

    if ($result_actualizar_sector) {
        echo "Sector actualizado con éxito.";
    } else {
        echo "Error al actualizar el sector: " . $conn->error;
    }
}

// Obtener información del sector desde la URL.
$sector_id = $_GET['id'];
$query_sector = "SELECT id, letra FROM sectores WHERE id = $sector_id";
$result_sector = $conn->query($query_sector);

if ($result_sector->num_rows === 1) {
    $row_sector = $result_sector->fetch_assoc();
    $letra_actual = $row_sector['letra'];
} else {
    echo "Sector no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Editar Sector</h2>

<!-- Formulario para editar el sector -->
<form method="post" action="">
    <label for="nueva_letra">Nueva Letra del Sector:</label>
    <input type="text" id="nueva_letra" name="nueva_letra" value="<?php echo $letra_actual; ?>" required>

    <input type="hidden" name="sector_id" value="<?php echo $sector_id; ?>">
    <button type="submit" name="editar_sector">Editar Sector</button>
</form>

</body>
</html>
