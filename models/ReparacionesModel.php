<?php
require_once __DIR__ . "/../clases/Reparacion.php";
require_once __DIR__ . "/../config/cn.php";

class ReparacionesModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

      public function crear(Reparacion $r) {
        $stmt = $this->db->prepare("INSERT INTO reparaciones (id_diagnostico, fecha_ingreso, fecha_entrega, estado, diagnostico, costo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $r->getIdDiagnostico(),
            $r->getFechaIngreso(),
            $r->getFechaEntrega(),
            $r->getEstado(),
            $r->getDiagnostico(),
            $r->getCosto()
        ]);
        $r->setId($this->db->lastInsertId());
    }

    public function obtenerPorDiagnostico($idDiagnostico) {
        $stmt = $this->db->prepare("SELECT * FROM reparaciones WHERE id_diagnostico = ?");
        $stmt->execute([$idDiagnostico]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $r = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
            return $r;
        }
        return null;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM reparaciones WHERE id_reparacion = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
        }
        return null;
    }

    public function actualizar(Reparacion $r) {
        $stmt = $this->db->prepare("UPDATE reparaciones SET fecha_ingreso = ?, fecha_entrega = ?, estado = ?, diagnostico = ?, costo = ? WHERE id_reparacion = ?");
        $stmt->execute([
            $r->getFechaIngreso(),
            $r->getFechaEntrega(),
            $r->getEstado(),
            $r->getDiagnostico(),
            $r->getCosto(),
            $r->getId()
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM reparaciones WHERE id_reparacion = ?");
        $stmt->execute([$id]);
    }
}
