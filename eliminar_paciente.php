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

// Lógica para procesar la eliminación del paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_paciente'])) {
    // Eliminar al paciente de la base de datos
    $eliminar_paciente = "DELETE FROM pacientes WHERE id = $paciente_id";
    $result_eliminar_paciente = $conn->query($eliminar_paciente);

    if ($result_eliminar_paciente) {
        echo "Paciente eliminado con éxito.";
        // Puedes redirigir o realizar otras acciones después de la eliminación
    } else {
        echo "Error al eliminar el paciente: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="admin.php">ATRAS</a>

<h2>Eliminar Paciente</h2>

<p><strong>Nombre del Paciente:</strong> <?php echo $nombre . ' ' . $apellido; ?></p>
<p><strong>Sector:</strong> <?php echo obtenerNombreSector($conn, $sector_id); ?></p>

<!-- Formulario para confirmar la eliminación del paciente -->
<form method="post" action="">
    <button type="submit" name="eliminar_paciente">Eliminar Paciente</button>
</form>

</body>
</html>

<?php
// Función para obtener el nombre del sector
function obtenerNombreSector($conn, $sector_id)
{
    $query_sector = "SELECT letra FROM sectores WHERE id = $sector_id";
    $result_sector = $conn->query($query_sector);

    if ($result_sector->num_rows === 1) {
        $row_sector = $result_sector->fetch_assoc();
        return $row_sector['letra'];
    } else {
        return "Desconocido";
    }
}
?>
