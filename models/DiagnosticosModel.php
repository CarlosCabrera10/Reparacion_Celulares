<?php
require_once __DIR__ . "/../config/cn.php";
require_once __DIR__ . "/../clases/Diagnostico.php";
require_once __DIR__ . "/ReparacionesModel.php"; 


class DiagnosticosModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
        $this->reparacionesModel = new ReparacionesModel(); 
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM diagnosticos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $diagnosticos = [];
        foreach ($rows as $row) {
            $d = new Diagnostico(
                $row['id_diagnostico'],
                $row['id_celular'],
                $row['id_empleado'],
                $row['fecha_diagnostico'],
                $row['descripcion']
            );
            $d->setEstado($row['estado']); // <-- Asegúrate de asignar el estado
            $diagnosticos[] = $d;
        }
        return $diagnosticos;
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM diagnosticos WHERE id_diagnostico=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $d = new Diagnostico(
            $row['id_diagnostico'],
            $row['id_celular'],
            $row['id_empleado'],
            $row['fecha_diagnostico'],
            $row['descripcion']
        );
        $d->setEstado($row['estado']); // <-- Asignar estado aquí también
        return $d;
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
        $stmt = $this->db->prepare("UPDATE diagnosticos SET id_celular=?, id_empleado=?, fecha_diagnostico=?, descripcion=?, estado=? WHERE id_diagnostico=?");
        return $stmt->execute([
            $diagnostico->getIdCelular(),
            $diagnostico->getIdEmpleado(),
            $diagnostico->getFechaDiagnostico(),
            $diagnostico->getDescripcion(),
            $diagnostico->getEstado(),  // <--- agregar esto
            $diagnostico->getId()
        ]);
    }

    public function toggleEstado(Diagnostico $diagnostico) {
        $nuevoEstado = $diagnostico->getEstado() === 'Activo' ? 'Cancelado' : 'Activo';

        // Actualizar diagnóstico
        $stmt = $this->db->prepare("UPDATE diagnosticos SET estado=? WHERE id_diagnostico=?");
        $stmt->execute([$nuevoEstado, $diagnostico->getId()]);

        // Actualizar reparación asociada si existe
        $reparacion = $this->reparacionesModel->obtenerPorDiagnostico($diagnostico->getId());
        if ($reparacion) {
            $estadoReparacion = $nuevoEstado === 'Cancelado' ? 'Cancelado' : 'Pendiente';
            $this->reparacionesModel->actualizarEstado($reparacion->getId(), $estadoReparacion);
        }

        return $nuevoEstado;
    }



    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM diagnosticos WHERE id_diagnostico=?");
        return $stmt->execute([$id]);
    }
}
