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

    // Buscar clientes por término (nombre o teléfono), devuelve array de Cliente
    public function buscarPorTermino($termino, $limit = 10) {
        $like = '%' . $termino . '%';
        // Buscar por nombre, teléfono o email
    $stmt = $this->db->prepare("SELECT * FROM clientes WHERE nombre LIKE :like OR telefono LIKE :like OR email LIKE :like LIMIT :limit");
    $stmt->bindValue(':like', $like, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($rows as $row) {
            $clientes[] = new Cliente($row['id_cliente'], $row['nombre'], $row['telefono'], $row['email']);
        }
        return $clientes;
    }

    public function crear(Cliente $cliente) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, telefono, email) VALUES (?, ?, ?)");
        $ok = $stmt->execute([$cliente->getNombre(), $cliente->getTelefono(), $cliente->getEmail()]);
        if ($ok) {
            // Devolver el id del cliente creado para permitir acciones encadenadas
            return $this->db->lastInsertId();
        }
        return false;
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
