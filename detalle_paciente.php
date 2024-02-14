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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="admin.php">ATRAS</a>
<h2>Detalles del Paciente</h2>
<p><strong>Nombre del Paciente:</strong> <?php echo $nombre . ' ' . $apellido; ?></p>
<p><strong>Sector:</strong> <?php echo obtenerNombreSector($conn, $sector_id); ?></p>

<!-- Puedes mostrar más detalles del paciente según tus necesidades -->


<!-- Lista de medicamentos asignados al paciente -->
<h3>Medicamentos Asignados</h3>
<table border="1">
    <thead>
        <tr>
            <th>Nombre del Medicamento</th>
            <th>Horario</th>
            <th>Dosis</th>
            <th>Detalles</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Obtener medicamentos asignados al paciente
        $query_medicamentos_asignados = "SELECT DISTINCT m.nombre AS nombre_medicamento, pm.horario, pm.dosis, pm.detalles
                                         FROM paciente_medicamentos pm
                                         JOIN medicamentos m ON pm.medicamento_id = m.id
                                         WHERE pm.paciente_id = $paciente_id";
        $result_medicamentos_asignados = $conn->query($query_medicamentos_asignados);

        // Mostrar los medicamentos asignados en la tabla
        while ($row_medicamento_asignado = $result_medicamentos_asignados->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_medicamento_asignado['nombre_medicamento']}</td>";
            echo "<td>{$row_medicamento_asignado['horario']}</td>";
            echo "<td>{$row_medicamento_asignado['dosis']}</td>";
            echo "<td>{$row_medicamento_asignado['detalles']}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
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


