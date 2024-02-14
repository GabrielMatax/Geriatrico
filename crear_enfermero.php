<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

// Lógica para agregar un nuevo enfermero.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_enfermero'])) {
    $nombre_enfermero = $_POST['nombre_enfermero'];
    $apellido_enfermero = $_POST['apellido_enfermero'];

    // Verificar si el enfermero ya existe.
    $existe_enfermero = "SELECT id FROM enfermeros WHERE nombre = '$nombre_enfermero' AND apellido = '$apellido_enfermero'";
    $result_existe_enfermero = $conn->query($existe_enfermero);

    if ($result_existe_enfermero->num_rows === 0) {
        // Agregar el nuevo enfermero.
        $agregar_enfermero = "INSERT INTO enfermeros (nombre,apellido) VALUES ('$nombre_enfermero','$apellido_enfermero')";
        $result_agregar_enfermero = $conn->query($agregar_enfermero);

        if ($result_agregar_enfermero) {
            echo "Enfermero agregado con éxito.";
            header('Location: admin.php');
            exit;
        } else {
            echo "Error al agregar el enfermero: " . $conn->error;
        }
    } else {
        echo "Error: El enfermero ya existe.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>



<a href="admin.php">ATRAS</a>

<H3>ENFERMEROS</H3>

<!-- Formulario para agregar un nuevo enfermero -->
<form method="post" action="crear_enfermero.php">
    <label for="nombre_enfermero">Nombre del Enfermero:</label>
    <input type="text" id="nombre_enfermero" name="nombre_enfermero" required>
    <input type="text" id="apellido_enfermero" name="apellido_enfermero" required>
    <button type="submit" name="crear_enfermero">Agregar Enfermero</button>
</form>

</body>
</html>