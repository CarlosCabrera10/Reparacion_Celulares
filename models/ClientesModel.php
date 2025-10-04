<?php
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/../clases/Cliente.php";

class ClientesModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM clientes");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($rows as $row) {
            $clientes[] = new Cliente(
                $row["id_cliente"],
                $row["nombre"],
                $row["telefono"],
                $row["email"]
            );
        }
        return $clientes;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Cliente($row['id_cliente'], $row['nombre'], $row['telefono'], $row['email']) : null;
    }

    public function crear(Cliente $cliente) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, telefono, email) VALUES (?, ?, ?)");
        return $stmt->execute([$cliente->getNombre(), $cliente->getTelefono(), $cliente->getEmail()]);
    }

    public function actualizar(Cliente $cliente) {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre=?, telefono=?, email=? WHERE id_cliente=?");
        return $stmt->execute([$cliente->getNombre(), $cliente->getTelefono(), $cliente->getEmail(), $cliente->getId()]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id_cliente=?");
        return $stmt->execute([$id]);
    }
}
