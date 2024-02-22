<?php

class Visita {
    public $paciente;
    public $cantidad;
    public $fecha;
    public $pagado;

    public static function cargarDesdeCSV($csvFilePath) {
        $visitas = [];
        $csvFile = file($csvFilePath);

        foreach ($csvFile as $line) {
            $data = explode(',', $line);

            $paciente = new class {
                public $name;
            };
            $paciente->name = trim($data[0]);

            $visitas[] = new Visita($paciente, (int)$data[1], trim($data[2]), $data[3] === 'True');
        }

        return $visitas;
    }

    public static function guardarEnCSV($visitas, $csvFilePath) {
        $lines = [];
        foreach ($visitas as $visita) {
            $lines[] = "{$visita->paciente->name},{$visita->cantidad},{$visita->fecha}," . ($visita->pagado ? 'True' : 'False');
        }
        file_put_contents($csvFilePath, implode(PHP_EOL, $lines));
    }

    public static function anadirVisita($visitas, $nuevaVisita) {
        // Clonamos el array existente para no modificarlo directamente
        $visitasNuevas = $visitas;
    
        // Añadimos la nueva visita al array clonado
        $visitasNuevas[] = $nuevaVisita;
    
        // Devolvemos el array clonado con la nueva visita
        return $visitasNuevas;
    }
    
    public static function eliminarVisita($visitas, $visitaId) {
        unset($visitas[$visitaId]);
        return array_values($visitas);
    }

    public function modificarVisita($nuevaCantidad, $nuevaFecha, $nuevoEstadoPago) {
        $this->cantidad = $nuevaCantidad;
        $this->fecha = DateTime::createFromFormat('Y-m-d', $nuevaFecha)->format('Y-m-d');
        $this->pagado = $nuevoEstadoPago;
    }

    public function __construct($paciente, $cantidad, $fecha, $pagado) {
        $this->paciente = $paciente;
        $this->cantidad = $cantidad;
        $this->fecha = DateTime::createFromFormat('Y-m-d', $fecha)->format('Y-m-d');
        $this->pagado = $pagado;
    }

    public function __toString() {
        return "Paciente: {$this->paciente->name}, Cantidad: {$this->cantidad}, Fecha: {$this->fecha}, Pagado: {$this->pagado}";
    }

    public function visualizarInformacion() {
        return $this->__toString();
    }
}



