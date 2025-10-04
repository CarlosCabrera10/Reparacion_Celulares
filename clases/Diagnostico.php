<?php
class Diagnostico {
    private $id;
    private $id_celular;
    private $id_empleado;
    private $fecha_diagnostico;
    private $descripcion;

    // Objetos relacionados
    private $celular;
    private $empleado;
    private $reparacion;


    public function __construct($id = null, $id_celular, $id_empleado, $fecha_diagnostico, $descripcion) {
        $this->id = $id;
        $this->id_celular = $id_celular;
        $this->id_empleado = $id_empleado;
        $this->fecha_diagnostico = $fecha_diagnostico;
        $this->descripcion = $descripcion;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdCelular() { return $this->id_celular; }
    public function getIdEmpleado() { return $this->id_empleado; }
    public function getFechaDiagnostico() { return $this->fecha_diagnostico; }
    public function getDescripcion() { return $this->descripcion; }

    public function getCelular() { return $this->celular; }
    public function getEmpleado() { return $this->empleado; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setIdCelular($id_celular) { $this->id_celular = $id_celular; }
    public function setIdEmpleado($id_empleado) { $this->id_empleado = $id_empleado; }
    public function setFechaDiagnostico($fecha_diagnostico) { $this->fecha_diagnostico = $fecha_diagnostico; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function setCelular($celular) { $this->celular = $celular; }
    public function setEmpleado($empleado) { $this->empleado = $empleado; }

    public function getReparacion() {
        return $this->reparacion;
    }

    public function setReparacion($reparacion) {
        $this->reparacion = $reparacion;
    }
}
