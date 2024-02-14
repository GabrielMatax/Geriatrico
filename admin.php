<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
include 'conexion.php';

// Lógica para mostrar la lista de enfermeros.
$query_enfermeros = "SELECT id, nombre, apellido FROM enfermeros";
$result_enfermeros = $conn->query($query_enfermeros);

// Lógica para mostrar la lista de pacientes.
$query_pacientes = "SELECT pacientes.id, pacientes.nombre, pacientes.apellido, sectores.letra AS letra_sector
                    FROM pacientes
                    INNER JOIN sectores ON pacientes.sector_id = sectores.id";
$result_pacientes = $conn->query($query_pacientes);


// Obtener la lista de sectores
$query_sectores = "SELECT id, letra FROM sectores";
$result_sectores = $conn->query($query_sectores);

// Obtener la lista de medicamentos
$query_medicamentos = "SELECT id, nombre, cantidad, fecha_vencimiento FROM medicamentos";
$result_medicamentos = $conn->query($query_medicamentos);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos específicos para esta página */
        #enfermeros, #pacientes, #sectores, #medicamentos {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #enfermeros h3, #pacientes h3, #sectores h3, #medicamentos h3 {
            margin-bottom: 10px;
        }

        @media screen and (max-width: 768px) {
            #enfermeros, #pacientes, #sectores, #medicamentos {
                padding: 15px;
            }
        }

        @media screen and (max-width: 576px) {
            #enfermeros, #pacientes, #sectores, #medicamentos {
                padding: 10px;
            }
        }

    </style>
</head>
<body>

<h2>Panel de Administración</h2>

<!-- Menú de navegación -->
<nav>
<ul>
    <li><a href="#enfermeros">Enfermeros</a></li>
    <li><a href="#pacientes">Pacientes</a></li>
    <li><a href="#sectores">Sectores</a></li>
    <li><a href="#medicamentos">Medicamentos</a></li>
    
    <!-- Agregar más secciones según tus necesidades -->
</ul>
</nav>
<br><br>

<!-- Contenido de la sección de Enfermeros -->
<div id="enfermeros">

<!-- Lista de enfermeros existentes -->
<h3>ENFERMEROS &emsp;&emsp; <a href="crear_enfermero.php">+</a></h3>
<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Acciones</th>
    </tr>
    <?php
    while ($row_enfermero = $result_enfermeros->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row_enfermero['nombre']}</td>";
        echo "<td>{$row_enfermero['apellido']}</td>";
        echo "<td>
                <a href='detalle_enfermero.php?id={$row_enfermero['id']}'>Ver Detalles</a> | 
                <a href='editar_enfermero.php?id={$row_enfermero['id']}'>Editar</a> | 
                <a href='eliminar_enfermero.php?id={$row_enfermero['id']}'>Eliminar</a> | 
                <a href='turno_enfermero.php?id={$row_enfermero['id']}'>Turnos</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>
</div>
<br><br>
<div id="pacientes">
    <!-- Lista de pacientes existentes -->
<h3>PACIENTES &emsp;&emsp; <a href="crear_paciente.php">+</a></h3>
<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Sector</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row_paciente = $result_pacientes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_paciente['nombre']}</td>";
            echo "<td>{$row_paciente['apellido']}</td>";
            echo "<td>{$row_paciente['letra_sector']}</td>";
            echo "<td><a href='detalle_paciente.php?id={$row_paciente['id']}'>Detalles</a> | <a href='editar_paciente.php?id={$row_paciente['id']}'>Editar</a> | <a href='eliminar_paciente.php?id={$row_paciente['id']}'>Eliminar</a> | <a href='paciente_medicamentos.php?id={$row_paciente['id']}'>Medicación</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
</div>
<br><br>
<div id="sectores">
<h3>SECTORES &emsp;&emsp; <a href="crear_sector.php">+</a></h3>
<!-- Mostrar la lista de sectores -->
<table border="1">
    <thead>
        <tr>
            <th>Sector</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row_sector = $result_sectores->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row_sector['letra']; ?></td>
                <td>
                    <!-- Enlaces para editar, eliminar y ver detalles del sector -->
                    <a href="editar_sector.php?id=<?php echo $row_sector['id']; ?>">Editar</a> |
                    <a href="eliminar_sector.php?id=<?php echo $row_sector['id']; ?>">Eliminar</a> |
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<br><br>

<div id="medicamentos">
<h3>MEDICAMENTOS &emsp;&emsp; <a href="crear_medicamento.php">+</a></h3>
    <!-- Mostrar la lista de medicamentos -->
<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Unidades</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row_medicamento = $result_medicamentos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_medicamento['nombre']}</td>";
            echo "<td>{$row_medicamento['cantidad']}</td>";
            echo "<td><a href='detalle_medicamento.php?id={$row_medicamento['id']}'>Detalles</a> | ";
            echo "<a href='editar_medicamento.php?id={$row_medicamento['id']}'>Editar</a> | ";
            echo "<a href='eliminar_medicamento.php?id={$row_medicamento['id']}'>Eliminar</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
</div>
</body>
</html>
