
<?php


class Paciente {
    public $id;
    public $name;
    public $address;

    public static function cargarDesdeCSV($csvFilePathPacientes) {
        $pacientes = [];
        $csvFile = file($csvFilePathPacientes);

        foreach ($csvFile as $line) {
            $data = explode(',', $line);
            $pacientes[] = new Paciente(
                isset($data[0]) ? (int)$data[0] : 0,
                isset($data[1]) ? trim($data[1]) : '',
                isset($data[2]) ? trim($data[2]) : ''
            );
        }

        return $pacientes;
    }

    public static function guardarEnCSV($pacientes, $csvFilePathPacientes) {
        $lines = [];
        foreach ($pacientes as $paciente) {
            $lines[] = "{$paciente->id},{$paciente->name},{$paciente->address}";
        }
        file_put_contents($csvFilePathPacientes, implode(PHP_EOL, $lines));
    }

    public static function anadirPaciente($pacientes, $nuevoPaciente) {
        $pacientes[] = $nuevoPaciente;
        return $pacientes;
    }

    public static function eliminarPaciente($pacientes, $pacienteId) {
        unset($pacientes[$pacienteId]);
        return array_values($pacientes);
    }

    public function modificarPaciente($nuevoNombre, $nuevaDireccion) {
        $this->name = $nuevoNombre;
        $this->address = $nuevaDireccion;
    }

    public function __construct($id, $name, $address) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
    }

    public function __toString() {
        return "ID: {$this->id}, Nombre: {$this->name}, Dirección: {$this->address}";
    }
}



