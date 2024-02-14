<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_medicamento'])) {
    // Obtener datos del formulario
    $id_paciente = $_POST['id_paciente'];
    $id_medicamento = $_POST['id_medicamento'];
    $horario = $_POST['horario'];
    $dosis = $_POST['dosis'];
    $detalles = $_POST['detalles'];

    // Validar y procesar la creación del medicamento asociado al paciente
    if (!empty($id_paciente) && !empty($id_medicamento) && !empty($horario) && !empty($dosis)) {
        // Insertar el nuevo medicamento en la tabla paciente_medicamentos
        $insertar_medicamento = "INSERT INTO paciente_medicamentos (paciente_id, medicamento_id, horario, dosis, detalles)
                                 VALUES ($id_paciente, $id_medicamento, '$horario', '$dosis', '$detalles')";
        $result_insertar_medicamento = $conn->query($insertar_medicamento);

        if ($result_insertar_medicamento) {
            echo "Medicamento creado y asociado al paciente con éxito.";
        } else {
            echo "Error al crear el medicamento: " . $conn->error;
        }
    } else {
        echo "Complete todos los campos del formulario.";
    }
}

// Obtener la id del paciente desde la URL
$id_paciente = $_GET['id'];

// Obtener información del paciente
$query_paciente = "SELECT id, nombre, apellido FROM pacientes WHERE id = $id_paciente";
$result_paciente = $conn->query($query_paciente);

// Verificar si se encontró al paciente
if ($result_paciente->num_rows === 1) {
    $row_paciente = $result_paciente->fetch_assoc();
    $nombre_paciente = $row_paciente['nombre'] . ' ' . $row_paciente['apellido'];
} else {
    // Manejar el caso en que no se encuentre al paciente
    echo "Paciente no encontrado.";
    exit;
}



// Obtener la lista de medicamentos disponibles (puedes adaptar según tu estructura)
$query_medicamentos = "SELECT id, nombre FROM medicamentos";
$result_medicamentos = $conn->query($query_medicamentos);

// Obtener medicamentos asignados al paciente
$query_medicamentos_lista = "SELECT m.id, m.nombre AS nombre_medicamento, pm.horario, pm.dosis, pm.detalles
                       FROM paciente_medicamentos pm
                       JOIN medicamentos m ON pm.medicamento_id = m.id
                       WHERE pm.paciente_id = $id_paciente";
$result_medicamentos_lista = $conn->query($query_medicamentos_lista);

// Procesar la eliminación del medicamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_medicamento'])) {
    $id_medicamento_eliminar = $_POST['id_medicamento'];
    // Implementa la lógica para eliminar el medicamento de la base de datos
    // Por ejemplo:
    $eliminar_medicamento_query = "DELETE FROM paciente_medicamentos WHERE paciente_id = $id_paciente AND medicamento_id = $id_medicamento_eliminar";
    $result_eliminar_medicamento = $conn->query($eliminar_medicamento_query);
    if ($result_eliminar_medicamento) {
        echo "Medicamento eliminado con éxito.";
        // Recargar la página para actualizar la lista de medicamentos
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error al eliminar el medicamento: " . $conn->error;
    }
}


// Cerrar la conexión a la base de datos al finalizar
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Medicamento para Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Medicamento para Paciente</h2>

<p><strong>Paciente:</strong> <?php echo $nombre_paciente; ?></p>

<!-- Formulario para crear medicamento asociado al paciente -->
<form method="post" action="">
    <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>">

    <label for="id_medicamento">Medicamento:</label>
    <select id="id_medicamento" name="id_medicamento" required>
        <?php
        // Mostrar la lista de medicamentos disponibles
        while ($row_medicamento = $result_medicamentos->fetch_assoc()) {
            echo "<option value='{$row_medicamento['id']}'>{$row_medicamento['nombre']}</option>";
        }
        ?>
    </select><br>

    <label for="horario">Horario:</label>
    <input type="time" id="horario" name="horario" required><br>

    <label for="dosis">Dosis:</label>
    <input type="text" id="dosis" name="dosis" required><br>

    <label for="detalles">Detalles:</label>
    <textarea id="detalles" name="detalles"></textarea><br>

    <button type="submit" name="crear_medicamento">Aceptar</button>
</form>

<!-- Lista de medicamentos asignados al paciente -->
<h3>Medicamentos Asignados</h3>
<table border="1">
    <thead>
        <tr>
            <th>Nombre del Medicamento</th>
            <th>Horario</th>
            <th>Dosis</th>
            <th>Detalles</th>
            <th>Acciones</th> <!-- Nueva columna para acciones -->
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row_medicamento = $result_medicamentos_lista->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_medicamento['nombre_medicamento']}</td>";
            echo "<td>{$row_medicamento['horario']}</td>";
            echo "<td>{$row_medicamento['dosis']}</td>";
            echo "<td>{$row_medicamento['detalles']}</td>";
            echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='id_medicamento' value='{$row_medicamento['id']}'> <!-- Cambio aquí -->
                        <button type='submit' name='eliminar_medicamento'>Eliminar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }        
        ?>
    </tbody>
</table>

</body>
</html>
