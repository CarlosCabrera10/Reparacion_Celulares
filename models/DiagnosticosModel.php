<?php
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/../clases/Diagnostico.php";


class DiagnosticosModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM diagnosticos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $diagnosticos = [];
        foreach ($rows as $row) {
            $diagnosticos[] = new Diagnostico(
                $row['id_diagnostico'],
                $row['id_celular'],
                $row['id_empleado'],
                $row['fecha_diagnostico'],
                $row['descripcion']
            );
        }
        return $diagnosticos;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM diagnosticos WHERE id_diagnostico=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Diagnostico(
            $row['id_diagnostico'],
            $row['id_celular'],
            $row['id_empleado'],
            $row['fecha_diagnostico'],
            $row['descripcion']
        ) : null;
    }

    public function crear(Diagnostico $diagnostico) {
        $stmt = $this->db->prepare(
            "INSERT INTO diagnosticos (id_celular, id_empleado, fecha_diagnostico, descripcion) 
            VALUES (?, ?, ?, ?)"
        );
        $resultado = $stmt->execute([
            $diagnostico->getIdCelular(),
            $diagnostico->getIdEmpleado(),
            $diagnostico->getFechaDiagnostico(),
            $diagnostico->getDescripcion()
        ]);

        if ($resultado) {
            $id = $this->db->lastInsertId();
            $diagnostico->setId($id); // Establecer el ID en el objeto también

            return $id; // Devuelve el ID insertado
        }
       
        return false;
    }

    public function actualizar(Diagnostico $diagnostico) {
        $stmt = $this->db->prepare("UPDATE diagnosticos SET id_celular=?, id_empleado=?, fecha_diagnostico=?, descripcion=? WHERE id_diagnostico=?");
        return $stmt->execute([
            $diagnostico->getIdCelular(),
            $diagnostico->getIdEmpleado(),
            $diagnostico->getFechaDiagnostico(),
            $diagnostico->getDescripcion(),
            $diagnostico->getId()
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM diagnosticos WHERE id_diagnostico=?");
        return $stmt->execute([$id]);
    }
}
