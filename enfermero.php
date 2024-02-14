<?php

session_start();
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'enfermero' && $_SESSION['rol'] !== 'admin')) {
    header('Location: index.php');
    exit;
}

include 'conexion.php'; // Asegúrate de tener el archivo de conexión

// Obtener la información del enfermero desde la base de datos
// Puedes adaptar esta consulta según la estructura de tu base de datos
$enfermero_id = $_SESSION['usuario_id']; // Asume que el ID del enfermero está en la sesión
$querya = "SELECT nombre, apellido FROM enfermeros WHERE id = $enfermero_id";
$resulta = $conn->query($querya);
$row = $resulta->fetch_assoc();
$nombre_enfermero = $row['nombre'] . ' ' . $row['apellido'];
$query_enfermero = "SELECT DISTINCT e.id AS enfermero_id, e.nombre AS enfermero_nombre, e.apellido AS enfermero_apellido,
                           s.letra AS sector_letra,
                           t.dia_semana, t.hora_inicio, t.hora_fin,
                           p.id AS paciente_id, p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
                           pm.horario AS medicamento_horario, m.nombre AS medicamento_nombre, pm.dosis, pm.detalles
                           
                    FROM enfermeros e
                    LEFT JOIN turnos t ON e.id = t.enfermero_id
                    LEFT JOIN sectores s ON t.sector_id = s.id
                    LEFT JOIN pacientes p ON t.enfermero_id = e.id
                    LEFT JOIN paciente_medicamentos pm ON p.id = pm.paciente_id
                    LEFT JOIN medicamentos m ON pm.medicamento_id = m.id
                    WHERE e.id = $enfermero_id
                    ORDER BY s.letra, t.dia_semana, t.hora_inicio, p.nombre, pm.horario";

$result_enfermero = $conn->query($query_enfermero);

// Cerrar la conexión a la base de datos al finalizar
$conn->close()
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página del Enfermero</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 5;
            padding: 0;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 600px) {
            /* Estilos para dispositivos móviles */
            table {
                width: 100%;
            }
            th, td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<a href="admin.php">ATRAS</a>

<h2>Bienvenido, <?php echo $nombre_enfermero ?></h2>

<?php
if ($result_enfermero->num_rows > 0) {
    $current_sector = '';
    $current_day = '';

    // Crear un array para almacenar los datos por día
    $data_by_day = array();

    while ($row = $result_enfermero->fetch_assoc()) {
        if ($current_sector != $row['sector_letra']) {
            // Nuevo sector
            $current_sector = $row['sector_letra'];
            echo "<h3>Sector $current_sector</h3>";
            echo "<br>";
        }

        $current_day = $row['dia_semana'];
        // Si no existe el día en el array, inicializa un array vacío
        if (!isset($data_by_day[$current_day])) {
            $data_by_day[$current_day] = array();
        }
        // Agrega los datos de la fila al array correspondiente al día de la semana
        $data_by_day[$current_day][] = $row;
    }

    // Ordenar el array por día de la semana
    $ordered_days = array('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo');
    uksort($data_by_day, function($a, $b) use ($ordered_days) {
        return array_search($a, $ordered_days) - array_search($b, $ordered_days);
    });

    // Recorre el array de datos por día para generar las tablas
    foreach ($data_by_day as $day => $data) {
        echo "<h3>" . mb_strtoupper($day, 'UTF-8') . " &nbsp;&nbsp;{$data[0]['hora_inicio']} - {$data[0]['hora_fin']}</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Paciente</th><th>Medicamento</th></tr>";
        
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>{$row['paciente_nombre']} {$row['paciente_apellido']}</td>";
            echo "<td>{$row['medicamento_nombre']} (Horario: {$row['medicamento_horario']}, Dosis: {$row['dosis']})</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<br>";
    }
} else {
    echo "No se encontró información para este enfermero.";
}
?>




</body>
</html>

