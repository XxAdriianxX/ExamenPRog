<?php
require_once __DIR__ . '/autoload.php';

// Cargar visitas desde CSV
$csvFilePathPacientes = 'pacientes.csv';
$pacientes = Paciente::cargarDesdeCSV($csvFilePathPacientes);

// Obtener el ID del paciente desde la URL
if (isset($_GET['paciente_id'])) {
    $pacienteId = $_GET['paciente_id'];

    // Verificar si el ID está dentro de los límites
    if ($pacienteId >= 0 && $pacienteId < count($pacientes)) {
        $paciente = $pacientes[$pacienteId];
    } else {
        // Redirigir si el ID está fuera de los límites
        header('Location: index.php');
        exit();
    }
} else {
    // Redirigir si no se proporciona el ID del paciente
    header('Location: index.php');
    exit();
}

// Obtener las visitas del paciente desde el archivo CSV
$visitas = Visita::cargarDesdeCSV('data.csv');
$visitasPaciente = [];
foreach ($visitas as $visita) {
    if ($visita->paciente->name === $paciente->name) {
        $visitasPaciente[] = $visita;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/styles.css">
    <title>Detalles del Paciente</title>
</head>
<body>
    <h1>Detalles del Paciente: <?= $paciente->name ?></h1>

    <h2>Próximas Visitas</h2>
    <table>
        <tr>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Estado de Pago</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
        <?php foreach ($visitasPaciente as $visitaId => $visita): ?>
            <tr class="<?= $visita->pagado ? 'pagado' : 'no-pagado'; ?> <?= ($visita->cantidad > 250) ? 'alta-cantidad' : ''; ?>">
                <td><?= $visita->cantidad ?></td>
                <td><?= $visita->fecha ?></td>
                <td><?= $visita->pagado ? 'Pagado' : 'No Pagado'; ?></td>
                <td>
                    <a href="modificar_visita.php?visita_id=<?= $visitaId ?>" class="btn btn-modificar">Modificar</a>
                </td>
                <td>
                    <a href="index.php?eliminar_visita=<?= $visitaId ?>" class="btn btn-eliminar">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php" class="btn">Volver a la Gestión de Visitas</a>

</body>
</html>


