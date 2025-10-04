<?php
class Ticket {
    private $id;
    private $id_reparacion;
    private $codigo_ticket;
    private $fecha_emision;

    public function __construct($id = null, $id_reparacion, $codigo_ticket, $fecha_emision = null) {
        $this->id = $id;
        $this->id_reparacion = $id_reparacion;
        $this->codigo_ticket = $codigo_ticket;
        $this->fecha_emision = $fecha_emision ?? date('Y-m-d H:i:s');
    }

    public function getId() { return $this->id; }
    public function getIdReparacion() { return $this->id_reparacion; }
    public function getCodigoTicket() { return $this->codigo_ticket; }
    public function getFechaEmision() { return $this->fecha_emision; }
}
