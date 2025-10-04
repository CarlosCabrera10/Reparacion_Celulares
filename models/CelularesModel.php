<?php
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/../clases/Celular.php";

class CelularesModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM celulares");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $celulares = [];
        foreach ($rows as $row) {
            $celulares[] = new Celular(
                $row['id_celular'],
                $row['id_cliente'],
                $row['marca'],
                $row['modelo'],
                $row['imei']
            );
        }
        return $celulares;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM celulares WHERE id_celular=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Celular($row['id_celular'], $row['id_cliente'], $row['marca'], $row['modelo'], $row['imei']) : null;
    }

    public function crear(Celular $celular) {
        $stmt = $this->db->prepare("INSERT INTO celulares (id_cliente, marca, modelo, imei) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $celular->getIdCliente(),
            $celular->getMarca(),
            $celular->getModelo(),
            $celular->getImei()
        ]);
    }

    public function actualizar(Celular $celular) {
        $stmt = $this->db->prepare("UPDATE celulares SET id_cliente=?, marca=?, modelo=?, imei=? WHERE id_celular=?");
        return $stmt->execute([
            $celular->getIdCliente(),
            $celular->getMarca(),
            $celular->getModelo(),
            $celular->getImei(),
            $celular->getId()
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM celulares WHERE id_celular=?");
        return $stmt->execute([$id]);
    }
}
