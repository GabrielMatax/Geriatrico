<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';


// Obtener el ID del enfermero desde la URL
$enfermero_id = $_GET['id'];

// Obtener información del enfermero
$query_enfermero = "SELECT id, nombre, apellido FROM enfermeros WHERE id = $enfermero_id";
$result_enfermero = $conn->query($query_enfermero);

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

// Lógica para procesar la creación del turno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_turno'])) {
    // Obtener detalles del turno desde el formulario
    $sectores = $_POST['sectores'];
    $dia_semana = $_POST['dia_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    // Validar y procesar la creación del turno
    if (!empty($dia_semana) && !empty($sectores) && !empty($hora_inicio) && !empty($hora_fin)) {
        // Insertar el nuevo turno en la base de datos
        foreach ($sectores as $sector_id) {
            $insertar_turno = "INSERT INTO turnos (enfermero_id,sector_id, dia_semana, hora_inicio, hora_fin, franco)
                            VALUES ($enfermero_id,$sector_id ,'$dia_semana', '$hora_inicio', '$hora_fin', 0)";
            $result_insertar_turno = $conn->query($insertar_turno);
        }
        if ($result_insertar_turno) {
            echo "Turno creado y asignado con éxito.";
            header('Location: detalle_enfermero.php?id=' . $enfermero_id);
           // header('Location: admin.php?id=' . $enfermero_id);
        } else {
            echo "Error al crear el turno: " . $conn->error;
        }
    } else {
        echo "Complete todos los campos del turno.";
    }
}

// Lógica para eliminar un turno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_turno'])) {
    // Obtener el ID del turno a eliminar
    $turno_id = $_POST['turno_id'];
    
    // Query para eliminar el turno
    $query_eliminar_turno = "DELETE FROM turnos WHERE id = $turno_id";
    if ($conn->query($query_eliminar_turno) === TRUE) {
        echo "<p>Turno eliminado correctamente.</p>";
        // Redireccionar para evitar la reenviación del formulario
        header("Refresh:0");
    } else {
        echo "<p>Error al eliminar el turno: " . $conn->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Turno a Enfermero</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Asignar Turno a Enfermero</h2>

<p><strong>Enfermero:</strong> <?php echo $nombre . ' ' . $apellido; ?></p>

<!-- Formulario para crear y asignar turno -->
<form method="post" action="">
<label for="dia_semana">Día de la semana:</label>
    <select id="dia_semana" name="dia_semana" required>
        <option value="lunes">Lunes</option>
        <option value="martes">Martes</option>
        <option value="miércoles">Miércoles</option>
        <option value="jueves">Jueves</option>
        <option value="viernes">Viernes</option>
        <option value="sábado">Sábado</option>
        <option value="domingo">Domingo</option>
    </select><br>

    <label for="sectores">Sectores:</label>
    <select id="sectores" name="sectores[]"  required>
        <?php
        // Obtener la lista de sectores
        $query_sectores = "SELECT id, letra FROM sectores";
        $result_sectores = $conn->query($query_sectores);

        // Mostrar opciones del select para cada sector
        while ($row_sector = $result_sectores->fetch_assoc()) {
            echo "<option value='{$row_sector['id']}'>{$row_sector['letra']}</option>";
        }
        ?>
    </select><br>

    <label for="hora_inicio">Hora de inicio:</label>
    <input type="time" id="hora_inicio" name="hora_inicio" required><br>

    <label for="hora_fin">Hora de fin:</label>
    <input type="time" id="hora_fin" name="hora_fin" required><br>

    <button type="submit" name="crear_turno">Crear y Asignar Turno</button>
</form>

<!-- Mostrar la lista de turnos asignados al enfermero -->
<h3>Turnos Asignados</h3>
<table border="1">
    <thead>
        <tr>
            <th>Día de la Semana</th>
            <th>Hora de Inicio</th>
            <th>Hora de Fin</th>
            <th>Sector</th>
            <th>Acciones</th>
            <!-- Agrega más encabezados si es necesario -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Obtener los turnos asignados al enfermero
        $query_turnos = "SELECT t.*, s.letra AS sector_letra FROM turnos t
                         INNER JOIN sectores s ON t.sector_id = s.id
                         WHERE enfermero_id = $enfermero_id";
        $result_turnos = $conn->query($query_turnos);

        // Mostrar los turnos en la tabla
        while ($row_turno = $result_turnos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_turno['dia_semana']}</td>";
            echo "<td>{$row_turno['hora_inicio']}</td>";
            echo "<td>{$row_turno['hora_fin']}</td>";
            echo "<td>{$row_turno['sector_letra']}</td>";
            echo "<td><form method='post' action=''>
            <input type='hidden' name='turno_id' value='{$row_turno['id']}'>
            <button type='submit' name='eliminar_turno'>Eliminar</button>
            </form></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>


</body>
</html>
