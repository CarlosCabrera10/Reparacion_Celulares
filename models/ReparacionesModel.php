<?php
require_once __DIR__ . "/../clases/Reparacion.php";
require_once __DIR__ . "/../config/cn.php";

class ReparacionesModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // ✅ Método para acceder a la conexión
    public function getDb() {
        return $this->db;
    }

    // ✅ Crear una reparación
    public function crear(Reparacion $r) {
        $stmt = $this->db->prepare("
            INSERT INTO reparaciones (id_diagnostico, fecha_ingreso, fecha_entrega, estado, diagnostico, costo)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
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

    // ✅ Obtener por ID
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

    // ✅ Obtener por diagnóstico
    public function obtenerPorDiagnostico($idDiagnostico) {
        $stmt = $this->db->prepare("SELECT * FROM reparaciones WHERE id_diagnostico = ?");
        $stmt->execute([$idDiagnostico]);
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

    // ✅ Obtener todas las reparaciones (sin joins)
    public function obtenerTodos() {
        $stmt = $this->db->prepare("SELECT * FROM reparaciones");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reparaciones = [];
        foreach ($filas as $fila) {
            $reparaciones[] = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
        }
        return $reparaciones;
    }

    // ✅ Obtener reparación con toda la información relacionada
    public function obtenerReparacionCompleta($id_reparacion) {
        $stmt = $this->db->prepare("
            SELECT r.*, d.id_empleado AS tecnico_id, c.nombre AS cliente_nombre, 
                   cel.marca, cel.modelo, cel.imei, e.nombre AS tecnico_nombre
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            INNER JOIN celulares cel ON d.id_celular = cel.id_celular 
            INNER JOIN clientes c ON cel.id_cliente = c.id_cliente 
            INNER JOIN empleados e ON d.id_empleado = e.id_empleado
            WHERE r.id_reparacion = ?
        ");
        $stmt->execute([$id_reparacion]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $reparacion = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
            $reparacion->cliente_nombre = $fila['cliente_nombre'];
            $reparacion->celular_marca = $fila['marca'];
            $reparacion->celular_modelo = $fila['modelo'];
            $reparacion->imei = $fila['imei'];
            $reparacion->tecnico_nombre = $fila['tecnico_nombre'];
            $reparacion->tecnico_id = $fila['tecnico_id'];
            return $reparacion;
        }
        return null;
    }

    // ✅ Actualizar reparación
    public function actualizar(Reparacion $r) {
        $stmt = $this->db->prepare("
            UPDATE reparaciones 
            SET fecha_ingreso = ?, fecha_entrega = ?, estado = ?, diagnostico = ?, costo = ? 
            WHERE id_reparacion = ?
        ");
        $resultado = $stmt->execute([
            $r->getFechaIngreso(),
            $r->getFechaEntrega(),
            $r->getEstado(),
            $r->getDiagnostico(),
            $r->getCosto(),
            $r->getId()
        ]);
        return $resultado && $stmt->rowCount() > 0;
    }

    // ✅ Eliminar reparación
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM reparaciones WHERE id_reparacion = ?");
        $stmt->execute([$id]);
    }

    // ✅ Obtener por técnico (todas sus reparaciones)
    public function obtenerPorTecnico($id_tecnico) {
        $stmt = $this->db->prepare("
            SELECT r.*, d.id_empleado AS tecnico_id, c.nombre AS cliente_nombre, 
                   cel.marca, cel.modelo, cel.imei 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            INNER JOIN celulares cel ON d.id_celular = cel.id_celular 
            INNER JOIN clientes c ON cel.id_cliente = c.id_cliente 
            WHERE d.id_empleado = ?
            ORDER BY r.fecha_ingreso DESC
        ");
        $stmt->execute([$id_tecnico]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reparaciones = [];
        foreach ($filas as $fila) {
            $reparacion = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
            $reparacion->cliente_nombre = $fila['cliente_nombre'];
            $reparacion->celular_marca = $fila['marca'];
            $reparacion->celular_modelo = $fila['modelo'];
            $reparacion->imei = $fila['imei'];
            $reparaciones[] = $reparacion;
        }
        return $reparaciones;
    }

    // ✅ Obtener todas (con joins)
    public function obtenerTodas() {
        $stmt = $this->db->prepare("
            SELECT r.*, d.id_empleado AS tecnico_id, 
                   c.nombre AS cliente_nombre, cel.marca, cel.modelo, cel.imei,
                   e.nombre AS tecnico_nombre
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            INNER JOIN celulares cel ON d.id_celular = cel.id_celular 
            INNER JOIN clientes c ON cel.id_cliente = c.id_cliente 
            INNER JOIN empleados e ON d.id_empleado = e.id_empleado
            ORDER BY r.fecha_ingreso DESC
        ");
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reparaciones = [];
        foreach ($filas as $fila) {
            $reparacion = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
            $reparacion->cliente_nombre = $fila['cliente_nombre'];
            $reparacion->celular_marca = $fila['marca'];
            $reparacion->celular_modelo = $fila['modelo'];
            $reparacion->imei = $fila['imei'];
            $reparacion->tecnico_nombre = $fila['tecnico_nombre'];
            $reparaciones[] = $reparacion;
        }
        return $reparaciones;
    }

    // ✅ Estadísticas por técnico
    public function obtenerEstadisticasTecnico($id_tecnico) {
        // Total de reparaciones
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ?
        ");
        $stmt->execute([$id_tecnico]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Reparaciones por estado
        $stmt = $this->db->prepare("
            SELECT r.estado, COUNT(*) AS cantidad 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ?
            GROUP BY r.estado
        ");
        $stmt->execute([$id_tecnico]);
        $porEstado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reparaciones del mes
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS este_mes 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ? 
            AND MONTH(r.fecha_ingreso) = MONTH(CURDATE()) 
            AND YEAR(r.fecha_ingreso) = YEAR(CURDATE())
        ");
        $stmt->execute([$id_tecnico]);
        $esteMes = $stmt->fetch(PDO::FETCH_ASSOC)['este_mes'];

        // Reparaciones completadas esta semana
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS esta_semana 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ? 
            AND (r.estado = 'Listo' OR r.estado = 'Entregado')
            AND WEEK(r.fecha_entrega) = WEEK(CURDATE()) 
            AND YEAR(r.fecha_entrega) = YEAR(CURDATE())
        ");
        $stmt->execute([$id_tecnico]);
        $completadasSemana = $stmt->fetch(PDO::FETCH_ASSOC)['esta_semana'];

        // Promedio de días entre ingreso y entrega
        $stmt = $this->db->prepare("
            SELECT AVG(DATEDIFF(r.fecha_entrega, r.fecha_ingreso)) AS promedio_dias 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ? 
            AND (r.estado = 'Listo' OR r.estado = 'Entregado')
            AND r.fecha_entrega IS NOT NULL
        ");
        $stmt->execute([$id_tecnico]);
        $promedioDias = $stmt->fetch(PDO::FETCH_ASSOC)['promedio_dias'] ?? 0;

        // Total generado
        $stmt = $this->db->prepare("
            SELECT SUM(r.costo) AS total_generado 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            WHERE d.id_empleado = ? 
            AND (r.estado = 'Listo' OR r.estado = 'Entregado')
            AND r.costo IS NOT NULL
        ");
        $stmt->execute([$id_tecnico]);
        $totalGenerado = $stmt->fetch(PDO::FETCH_ASSOC)['total_generado'] ?? 0;

        return [
            'total' => $total,
            'por_estado' => $porEstado,
            'este_mes' => $esteMes,
            'completadas_semana' => $completadasSemana,
            'promedio_dias' => round($promedioDias, 1),
            'total_generado' => $totalGenerado
        ];
    }

    // ✅ Reparaciones recientes
    public function obtenerReparacionesRecientes($id_tecnico, $limite = 5) {
        $limite = (int)$limite;
        if ($limite <= 0) $limite = 5;

        $sql = "
            SELECT r.*, d.id_empleado AS tecnico_id, c.nombre AS cliente_nombre, 
                   cel.marca, cel.modelo, cel.imei 
            FROM reparaciones r 
            INNER JOIN diagnosticos d ON r.id_diagnostico = d.id_diagnostico 
            INNER JOIN celulares cel ON d.id_celular = cel.id_celular 
            INNER JOIN clientes c ON cel.id_cliente = c.id_cliente 
            WHERE d.id_empleado = ?
            ORDER BY r.fecha_ingreso DESC
            LIMIT $limite
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_tecnico]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reparaciones = [];
        foreach ($filas as $fila) {
            $reparacion = new Reparacion(
                $fila['id_reparacion'],
                $fila['id_diagnostico'],
                $fila['fecha_ingreso'],
                $fila['fecha_entrega'],
                $fila['estado'],
                $fila['diagnostico'],
                $fila['costo']
            );
            $reparacion->cliente_nombre = $fila['cliente_nombre'];
            $reparacion->celular_marca = $fila['marca'];
            $reparacion->celular_modelo = $fila['modelo'];
            $reparacion->imei = $fila['imei'];
            $reparaciones[] = $reparacion;
        }
        return $reparaciones;
    }
}
