<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    include 'conexion.php';

    // Validar las credenciales del administrador.
    $query = "SELECT id, username, password FROM administradores WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verificar la contraseña con password_verify.
        if (password_verify($password, $hashed_password)) {
            $_SESSION['rol'] = 'admin';
            ob_end_clean(); // Limpiar el buffer de salida
            header('Location: admin.php');
            exit;
        } else {
            $error_message = "ACredenciales incorrectas. Por favor, intenta nuevamente.";
        }
    } else {
        $error_message = "BCredenciales incorrectas. Por favor, intenta nuevamente.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login de Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Login de Administrador</h2>

<?php
if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form action="admin.php" method="post">
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Iniciar Sesión</button>
</form>

</body>
</html>
