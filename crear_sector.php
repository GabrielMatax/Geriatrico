<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_sector'])) {
    $letra_sector = $_POST['letra_sector'];

    // Verificar si el sector ya existe.
    $existe_sector = "SELECT id FROM sectores WHERE letra = '$letra_sector'";
    $result_existe_sector = $conn->query($existe_sector);

    if ($result_existe_sector->num_rows === 0) {
        // Agregar el nuevo sector.
        $agregar_sector = "INSERT INTO sectores (letra) VALUES ('$letra_sector')";
        $result_agregar_sector = $conn->query($agregar_sector);

        if ($result_agregar_sector) {
            echo "Sector agregado con éxito.";
        } else {
            echo "Error al agregar el sector: " . $conn->error;
        }
    } else {
        echo "Error: El sector ya existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Sector</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="admin.php">ATRAS</a>
<h2>Agregar Nuevo Sector</h2>

<!-- Formulario para agregar un nuevo sector -->
<form method="post" action="">
    <label for="letra_sector">Letra del Sector:</label>
    <input type="text" id="letra_sector" name="letra_sector" required>

    <button type="submit" name="agregar_sector">Agregar Sector</button>
</form>

</body>
</html>
