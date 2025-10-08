<?php
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/../clases/Empleado.php";

class EmpleadoModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM empleados");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $empleados = [];
        foreach ($rows as $row) {
            $empleados[] = new Empleado(
                $row["id_empleado"],
                $row["nombre"],
                $row["cargo"],
                $row["usuario"] ?? null,
                $row["password"] ?? null,
                $row["activo"] ?? 1 
            );
        }
        return $empleados;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM empleados WHERE id_empleado = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Empleado(
            $row["id_empleado"],
            $row["nombre"],
            $row["cargo"],
            $row["usuario"] ?? null,
            $row["password"] ?? null,
            $row["activo"] ?? 1 
        ) : null;
    }


    // Crear un empleado
    public function crear(Empleado $empleado) {
        $stmt = $this->db->prepare("INSERT INTO Empleados (nombre, cargo, usuario, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $empleado->getNombre(),
            $empleado->getCargo(),
            $empleado->getUsuario(),
            $empleado->getPassword()
        ]);
    }

    // Actualizar un empleado
    public function actualizar(Empleado $empleado) {
        $stmt = $this->db->prepare("UPDATE Empleados SET nombre=?, cargo=?, usuario=?, password=? WHERE id_empleado=?");
        return $stmt->execute([
            $empleado->getNombre(),
            $empleado->getCargo(),
            $empleado->getUsuario(),
            $empleado->getPassword(),
            $empleado->getId()
        ]);
    }

    // Eliminar un empleado
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM Empleados WHERE id_empleado=?");
        return $stmt->execute([$id]);
    }

    // Obtener un empleado por su usuario (para login)
    public function obtenerPorUsuario($usuario) {
        $stmt = $this->db->prepare("SELECT * FROM Empleados WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Empleado(
            $row["id_empleado"],
            $row["nombre"],
            $row["cargo"],
            $row["usuario"],
            $row["password"]
        ) : null;
    }

    public function actualizarEstado($id, $activo) {
        $stmt = $this->db->prepare("UPDATE empleados SET activo = ? WHERE id_empleado = ?");
        return $stmt->execute([$activo, $id]);
    }

}
