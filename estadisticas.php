<?php
require_once __DIR__ . '/autoload.php';

// Cargar todas las visitas y pacientes
$visitas = Visita::cargarDesdeCSV('data.csv');
$pacientes = Paciente::cargarDesdeCSV('pacientes.csv');

// Calcular estadísticas
$totalFacturas = calcularTotalFacturas($visitas);
$totalCobrado = calcularTotalCobrado($visitas);
$totalNoCobrado = calcularTotalNoCobrado($visitas);
$balanceTotal = $totalCobrado - $totalNoCobrado;
$numCobradas = contarVisitasCobradas($visitas);
$numNoCobradas = contarVisitasNoCobradas($visitas);
$totalPacientes = count($pacientes);
$totalPagadas = contarPacientesConTodasPagadas($pacientes, $visitas);
$totalNoPagadas = contarPacientesConAlgunaNoPagada($pacientes, $visitas);

// Funciones de cálculo
function calcularTotalFacturas($visitas) {
    $totalFacturas = 0;

    foreach ($visitas as $visita) {
        $totalFacturas += $visita->cantidad;
    }

    return $totalFacturas;
}

function calcularTotalCobrado($visitas) {
    $totalCobrado = 0;

    foreach ($visitas as $visita) {
        if ($visita->pagado) {
            $totalCobrado += $visita->cantidad;
        }
    }

    return $totalCobrado;
}

function calcularTotalNoCobrado($visitas) {
    $totalNoCobrado = 0;

    foreach ($visitas as $visita) {
        if (!$visita->pagado) {
            $totalNoCobrado += $visita->cantidad;
        }
    }

    return $totalNoCobrado;
}

function contarVisitasCobradas($visitas) {
    $numCobradas = 0;

    foreach ($visitas as $visita) {
        if ($visita->pagado) {
            $numCobradas++;
        }
    }

    return $numCobradas;
}

function contarVisitasNoCobradas($visitas) {
    $numNoCobradas = 0;

    foreach ($visitas as $visita) {
        if (!$visita->pagado) {
            $numNoCobradas++;
        }
    }

    return $numNoCobradas;
}

function contarPacientesConTodasPagadas($pacientes, $visitas) {
    $totalPagadas = 0;

    foreach ($pacientes as $paciente) {
        if (tieneTodasPagadas($paciente, $visitas)) {
            $totalPagadas++;
        }
    }

    return $totalPagadas;
}

function contarPacientesConAlgunaNoPagada($pacientes, $visitas) {
    $totalNoPagadas = 0;

    foreach ($pacientes as $paciente) {
        if (tieneAlgunaNoPagada($paciente, $visitas)) {
            $totalNoPagadas++;
        }
    }

    return $totalNoPagadas;
}

// Funciones auxiliares
function tieneTodasPagadas($paciente, $visitas) {
    foreach ($visitas as $visita) {
        if ($visita->paciente->name === $paciente->name && !$visita->pagado) {
            return false; // Si alguna visita no está pagada, retorna falso
        }
    }

    return true; // Si todas las visitas están pagadas, retorna verdadero
}

function tieneAlgunaNoPagada($paciente, $visitas) {
    foreach ($visitas as $visita) {
        if ($visita->paciente->name === $paciente->name && !$visita->pagado) {
            return true; // Si al menos una visita no está pagada, retorna verdadero
        }
    }

    return false; // Si todas las visitas están pagadas, retorna falso
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/styles.css">
    <title>Estadísticas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .estadistica {
            margin-bottom: 20px;
        }

        .estadistica label {
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>
<h1>Estadísticas Generales</h1>

<table>
    <tr>
        <th>Estadística</th>
        <th>Valor</th>
    </tr>
    <tr>
        <td>Total de € en facturas</td>
        <td><?= $totalFacturas ?></td>
    </tr>
    <tr>
        <td>Total de € de visitas cobradas</td>
        <td><?= $totalCobrado ?></td>
    </tr>
    <tr>
        <td>Total de € de visitas no cobradas</td>
        <td><?= $totalNoCobrado ?></td>
    </tr>
    <tr>
        <td>Balance total</td>
        <td><?= $balanceTotal ?></td>
    </tr>
    <tr>
        <td>Número de visitas cobradas</td>
        <td><?= $numCobradas ?></td>
    </tr>
    <tr>
        <td>Número de visitas no cobradas</td>
        <td><?= $numNoCobradas ?></td>
    </tr>
    <tr>
        <td>Total de pacientes</td>
        <td><?= $totalPacientes ?></td>
    </tr>
    <tr>
        <td>Total de pacientes con todas las visitas pagadas</td>
        <td><?= $totalPagadas ?></td>
    </tr>
    <tr>
        <td>Total de pacientes con alguna visita no pagada</td>
        <td><?= $totalNoPagadas ?></td>
    </tr>
</table>

<a href="index.php" class="btn">Volver a la Gestión de Visitas</a>
</body>
</html>

</body>
</html>

