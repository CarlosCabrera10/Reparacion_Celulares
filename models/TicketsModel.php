<?php
require_once __DIR__ . "/../clases/Ticket.php";
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/TicketsModel.php";



class TicketsModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function crear(Ticket $ticket) {
        $stmt = $this->db->prepare(
            "INSERT INTO tickets (id_reparacion, codigo_ticket, fecha_emision) VALUES (?, ?, ?)"
        );
        $resultado = $stmt->execute([
            $ticket->getIdReparacion(),
            $ticket->getCodigoTicket(),
            $ticket->getFechaEmision()
        ]);

        if ($resultado) return $this->db->lastInsertId();
        return false;
    }

    public function obtenerPorId($id_ticket) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id_ticket = :id_ticket");
        $stmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Ticket(
                $row['id_ticket'],
                $row['id_reparacion'],
                $row['codigo_ticket'],
                $row['fecha_emision']
            );
        }
        return null;
    }

    public function obtenerPorReparacion($id_reparacion) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id_reparacion = :id_reparacion");
        $stmt->bindParam(':id_reparacion', $id_reparacion, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Ticket(
                $row['id_ticket'],
                $row['id_reparacion'],
                $row['codigo_ticket'],
                $row['fecha_emision']
            );
        }
        return null;
    }

    public function generarCodigo() {
        return 'TCK-' . strtoupper(bin2hex(random_bytes(5))); // Código único, ej: TCK-1A2B3C4D5E
    }
}
