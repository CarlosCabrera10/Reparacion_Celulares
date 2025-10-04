<?php
require_once __DIR__ . "/../config/cn.php";

class ReportesModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Reparaciones por mes
    public function reparacionesPorMes() {
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(fecha_ingreso, '%Y-%m') AS mes, COUNT(*) AS total,
                SUM(CASE WHEN estado='Pendiente' THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estado='En Reparacion' THEN 1 ELSE 0 END) AS en_reparacion,
                SUM(CASE WHEN estado='Listo' THEN 1 ELSE 0 END) AS listos,
                SUM(CASE WHEN estado='Entregado' THEN 1 ELSE 0 END) AS entregados
            FROM reparaciones
            GROUP BY mes
            ORDER BY mes ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reparaciones por técnico
    public function reparacionesPorTecnico() {
        $stmt = $this->db->query("
            SELECT e.nombre AS tecnico, COUNT(r.id_reparacion) AS total,
                AVG(DATEDIFF(r.fecha_entrega, r.fecha_ingreso)) AS tiempo_promedio
            FROM reparaciones r
            INNER JOIN diagnosticos d ON r.id_diagnostico=d.id_diagnostico
            INNER JOIN empleados e ON d.id_empleado=e.id_empleado
            GROUP BY e.id_empleado
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reparaciones por marca
    public function reparacionesPorMarca() {
        $stmt = $this->db->query("
            SELECT c.marca, COUNT(r.id_reparacion) AS total
            FROM reparaciones r
            INNER JOIN diagnosticos d ON r.id_diagnostico=d.id_diagnostico
            INNER JOIN celulares c ON d.id_celular=c.id_celular
            GROUP BY c.marca
            ORDER BY total DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
