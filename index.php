<?php
session_start();

// Procesar la selección del rol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol'];

    // Mensaje de depuración
    echo "Rol seleccionado: $rol";

    if ($rol === 'enfermero') {
        // Mensaje de depuración
        echo " Redirigiendo a enfermero.php";
        
        // Aquí puedes realizar las acciones necesarias para la página de enfermero.
        // Por ejemplo, redirige a la página de enfermero.php.
        $_SESSION['rol'] = 'enfermero';
        header('Location: seleccionar_enfermero.php');
        exit;
    } elseif ($rol === 'admin') {
        // Mensaje de depuración
        echo " Redirigiendo a login_admin.php";
        
        // Aquí puedes realizar las acciones necesarias para la página de administrador.
        // Por ejemplo, redirige a la página de login_admin.php.
        $_SESSION['rol'] = 'admin';
        header('Location: login_admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Bienvenido</h2>

<form action="index.php" method="post">
    <label for="rol">Selecciona tu rol:</label>
    <select id="rol" name="rol">
        <option value="enfermero">Enfermero</option>
        <option value="admin">Administrador</option>
    </select>
    <button type="submit">Iniciar Sesión</button>
</form>

</body>
</html>
