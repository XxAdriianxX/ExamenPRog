<?php
// Autoloader
require_once __DIR__ . '/autoload.php';

// Cargar visitas desde CSV
$csvFilePath = 'data.csv';
$visitas = Visita::cargarDesdeCSV($csvFilePath);

// Añadir nueva visita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombrePaciente = $_POST['nombre_paciente'];
    $cantidad = (int)$_POST['cantidad'];
    $fecha = $_POST['fecha'];
    $pagado = $_POST['pagado'] === '1';

    // Crear una nueva instancia de Visita
    $nuevaVisita = new Visita((object)['name' => $nombrePaciente], $cantidad, $fecha, $pagado);

    // Añadir la nueva visita al array de visitas
    $visitas = Visita::anadirVisita($visitas, $nuevaVisita);

    // Guardar cambios en CSV
    Visita::guardarEnCSV($visitas, $csvFilePath);

    // Redirigir para evitar el reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


// Eliminar visita
if (isset($_GET['eliminar_visita'])) {
    $visitaId = (int)$_GET['eliminar_visita'];
    $visitas = Visita::eliminarVisita($visitas, $visitaId);

    // Guardar cambios en CSV
    Visita::guardarEnCSV($visitas, $csvFilePath);

    // Redirigir para evitar el reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/styles.css">
    <title>Clínica Saca-Muelas</title>
</head>
<body>
    <h1>Clínica Saca-Muelas - Gestión de Visitas</h1>

    <h2>Visitas</h2> <a href="estadisticas.php" class="btn">Ver Estadísticas</a>
    <table>
        <tr>
            <th>Paciente</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Estado de Pago</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
        <?php foreach ($visitas as $visitaId => $visita): ?>
            <tr class="<?= $visita->pagado ? 'pagado' : 'no-pagado'; ?> <?= ($visita->cantidad > 250) ? 'alta-cantidad' : ''; ?>">
                <td>
                    <!-- Enlace al nombre del paciente que lleva a detalles_paciente.php -->
                    <a href="detalles_pacientes.php?paciente_id=<?= $visitaId ?>">
                        <?= $visita->paciente->name ?>
                    </a>
                </td>
                <td><?= $visita->cantidad ?></td>
                <td><?= $visita->fecha ?></td>
                <td><?= $visita->pagado ? 'Pagado' : 'No Pagado'; ?></td>
                <td>
                    <a href="modificar_visita.php?visita_id=<?= $visitaId ?>" class="btn btn-modificar">Modificar</a>
                </td>
                <td>
                    <a href="?eliminar_visita=<?= $visitaId ?>" class="btn btn-eliminar">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Añadir Nueva Visita</h2>
    <form action="index.php" method="post">
        <label for="nombre_paciente">Nombre del Paciente:</label>
        <input type="text" name="nombre_paciente" required>
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" required>
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required>
        <label for="pagado">Pagado:</label>
        <select name="pagado" required>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
        <input type="submit" value="Añadir Visita" class="btn">
    </form>

</body>
</html>









