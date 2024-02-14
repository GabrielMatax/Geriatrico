<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

// Obtener el ID del enfermero desde la URL
$enfermero_id = $_GET['id'];

// Consulta para obtener información del enfermero
$query_enfermero = "SELECT  id, nombre, apellido FROM enfermeros WHERE id = $enfermero_id";
$result_enfermero = $conn->query($query_enfermero);

// Consulta para obtener información de los turnos del enfermero
$query_turnos = "SELECT DISTINCT dia_semana, hora_inicio, hora_fin 
                 FROM turnos 
                 WHERE enfermero_id = $enfermero_id 
                 ORDER BY FIELD(dia_semana, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo')";
$result_turnos = $conn->query($query_turnos);

// Consulta para obtener información del sector del enfermero
$query_sector = "SELECT  s.letra as sector_nombre FROM sectores s 
                 INNER JOIN turnos t ON s.id = t.sector_id 
                 WHERE t.enfermero_id = $enfermero_id LIMIT 1";
$result_sector = $conn->query($query_sector);

// Verificar si se encontró al enfermero
if ($result_enfermero->num_rows === 1) {
    $row_enfermero = $result_enfermero->fetch_assoc();
    $nombre = $row_enfermero['nombre'];
    $apellido = $row_enfermero['apellido'];
} else {
    // Manejar el caso en que no se encuentre al enfermero
    echo "Enfermero no encontrado.";
    exit;
}

// Obtener el nombre del sector del enfermero
if ($result_sector->num_rows === 1) {
    $row_sector = $result_sector->fetch_assoc();
    $sector = $row_sector['sector_nombre'];
} else {
    $sector = "No asignado";
}

// Obtener los turnos del enfermero
$turnos = array();
while ($row_turno = $result_turnos->fetch_assoc()) {
    $turnos[] = $row_turno;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Enfermero</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Detalles del Enfermero</h2>

<p><strong>Nombre:</strong> <?php echo $nombre . ' ' . $apellido; ?></p>
<p><strong>Sector:</strong> <?php echo $sector; ?></p>

<?php if (!empty($turnos)) : ?>
    <h3>Turnos del Enfermero</h3>
    <ul>
        <?php foreach ($turnos as $turno) : ?>
            <li>Día: <?php echo $turno['dia_semana']; ?>, Hora de inicio: <?php echo $turno['hora_inicio']; ?>, Hora de fin: <?php echo $turno['hora_fin']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No se encontraron turnos para este enfermero.</p>
<?php endif; ?>

</body>
</html>
