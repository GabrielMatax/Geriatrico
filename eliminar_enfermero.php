<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

$form_mostrado = true;
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_enfermero'])) {
    // Se enviaron datos por POST, procesar la eliminación
    $enfermero_id = $_POST['id'];

    // Validar y procesar la eliminación
    if (!empty($enfermero_id)) {
        // Eliminar al enfermero de la base de datos
        $eliminar_enfermero = "DELETE FROM enfermeros WHERE id = $enfermero_id";
        $result_eliminar_enfermero = $conn->query($eliminar_enfermero);

        if ($result_eliminar_enfermero) {
            // La eliminación fue exitosa
            $form_mostrado = false;
            $resultado = "Enfermero eliminado con éxito.";
        } else {
            $resultado = "Error al eliminar el enfermero: " . $conn->error;
        }
    } else {
        $resultado = "ID de enfermero inválido.";
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
    <title>Eliminar Enfermero</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>

<h2>Eliminar Enfermero</h2>

<?php
if ($form_mostrado) {
    // Mostrar el formulario de eliminación
    ?>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $row_enfermero['id']; ?>">
        <p>¿Estás seguro de que deseas eliminar al enfermero <?php echo $row_enfermero['nombre'] . ' ' . $row_enfermero['apellido']; ?>?</p>
        <button type="submit" name="eliminar_enfermero">Eliminar Enfermero</button>
    </form>
    <?php
} else {
    // Mostrar el resultado del procesamiento
    echo $resultado;
}
?>

</body>
</html>
