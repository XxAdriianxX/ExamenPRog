<?php

require_once __DIR__ . '/autoload.php';

// Cargar visitas desde CSV
$csvFilePath = 'data.csv';
$visitas = Visita::cargarDesdeCSV($csvFilePath);

// Obtener el ID de la visita a modificar
if (isset($_GET['visita_id'])) {
    $visita_id = (int)$_GET['visita_id'];
} else {
    die("Error: No se proporcionó un ID de visita para modificar.");
}

// Obtener la información de la visita a modificar
$visita = $visitas[$visita_id];

// Procesar el formulario de modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_visita'])) {
    $nueva_cantidad = (int)$_POST['nueva_cantidad'];
    $nueva_fecha = $_POST['nueva_fecha'];
    $nuevo_estado_pago = $_POST['nuevo_estado_pago'] === '1';

    // Modificar la visita en el array
    $visita->modificarVisita($nueva_cantidad, $nueva_fecha, $nuevo_estado_pago);

    // Guardar el array actualizado en el archivo CSV
    Visita::guardarEnCSV($visitas, $csvFilePath);

    // Redirigir de nuevo a index.php
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/styles.css">
    <title>Modificar Visita</title>
</head>
<body>

    <h1>Modificar Visita</h1>

    <form method="post" action="modificar_visita.php?visita_id=<?= $visita_id ?>">
        <label for="nueva_cantidad">Nueva Cantidad:</label>
        <input type="number" name="nueva_cantidad" value="<?= $visita->cantidad ?>" required>
        
        <label for="nueva_fecha">Nueva Fecha:</label>
        <input type="date" name="nueva_fecha" value="<?= $visita->fecha ?>" required>

        <label for="nuevo_estado_pago">Nuevo Estado de Pago:</label>
        <select name="nuevo_estado_pago" required>
            <option value="1" <?= $visita->pagado ? 'selected' : ''; ?>>Sí</option>
            <option value="0" <?= !$visita->pagado ? 'selected' : ''; ?>>No</option>
        </select>

        <input type="hidden" name="visita_id" value="<?= $visita_id ?>">
        <input type="submit" name="modificar_visita" value="Modificar Visita" class="btn">
    </form>

    <br>

    <a href="index.php" class="btn">Volver a la Gestión de Visitas</a>

</body>
</html>
