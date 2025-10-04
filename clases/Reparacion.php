<?php
class Reparacion {
    private $id;
    private $id_diagnostico;
    private $fecha_ingreso;
    private $fecha_entrega;
    private $estado;
    private $diagnostico;
    private $costo;
    private $ticket;

    public function __construct($id = null, $id_diagnostico, $fecha_ingreso, $fecha_entrega = null, $estado = 'Pendiente', $diagnostico = null, $costo = null) {
        $this->id = $id;
        $this->id_diagnostico = $id_diagnostico;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->fecha_entrega = $fecha_entrega;
        $this->estado = $estado;
        $this->diagnostico = $diagnostico;
        $this->costo = $costo;
    }

    public function getId() { return $this->id; }
    public function getIdDiagnostico() { return $this->id_diagnostico; }
    public function getFechaIngreso() { return $this->fecha_ingreso; }
    public function getFechaEntrega() { return $this->fecha_entrega; }
    public function getEstado() { return $this->estado; }
    public function getDiagnostico() { return $this->diagnostico; }
    public function getCosto() { return $this->costo; }

    public function setId($id) { $this->id = $id; }
    public function setIdDiagnostico($id_diagnostico) { $this->id_diagnostico = $id_diagnostico; }
    public function setFechaIngreso($fecha_ingreso) { $this->fecha_ingreso = $fecha_ingreso; }
    public function setFechaEntrega($fecha_entrega) { $this->fecha_entrega = $fecha_entrega; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setDiagnostico($diagnostico) { $this->diagnostico = $diagnostico; }
    public function setCosto($costo) { $this->costo = $costo; }

    public function getTicket() { return $this->ticket; }
    public function setTicket($ticket) { $this->ticket = $ticket; }
}
