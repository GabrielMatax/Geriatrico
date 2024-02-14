<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

$form_mostrado = true;
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_enfermero'])) {
    // Se enviaron datos por POST, procesar la edición
    $enfermero_id = $_POST['id'];
    $nuevo_nombre = $_POST['nombre_enfermero'];
    $nuevo_apellido = $_POST['apellido_enfermero'];

    // Validar y procesar la edición
    if (!empty($nuevo_nombre)) {
        // Actualizar la información del enfermero en la base de datos
        $actualizar_enfermero = "UPDATE enfermeros SET nombre = '$nuevo_nombre' AND SET apellido = '$nuevo_apellido' WHERE id = $enfermero_id";
        $result_actualizar_enfermero = $conn->query($actualizar_enfermero);

        if ($result_actualizar_enfermero) {
            // La edición fue exitosa
            $form_mostrado = false;
            $resultado = "Enfermero editado con éxito.";
        } else {
            $resultado = "Error al editar el enfermero: " . $conn->error;
        }
    } else {
        $resultado = "Por favor, completa todos los campos.";
    }
}

// Obtener la información actual del enfermero
$enfermero_id = $_GET['id'];
$query_enfermero = "SELECT * FROM enfermeros WHERE id = $enfermero_id";
$result_enfermero = $conn->query($query_enfermero);
$row_enfermero = $result_enfermero->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Enfermero</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>

<h2>Editar Enfermero</h2>

<?php
if ($form_mostrado) {
    // Mostrar el formulario de edición
    ?>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $row_enfermero['id']; ?>">
        <label for="nombre_enfermero">Nombre Nuevo:</label>
        <input type="text" id="nombre_enfermero" name="nombre_enfermero" value="<?php echo $row_enfermero['nombre']; ?>" required><br>
        <label for="apellido_enfermero">Apellido Nuevo:</label>
        <input type="text" id="apellido_enfermero" name="apellido_enfermero" value="<?php echo $row_enfermero['apellido']; ?>" required><br>
        <button type="submit" name="editar_enfermero">Editar Enfermero</button>
    </form>
    <?php
} else {
    // Mostrar el resultado del procesamiento
    echo $resultado;
}
?>

</body>
</html>
