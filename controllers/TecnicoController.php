<?php
require_once __DIR__ . "/../models/ReparacionesModel.php";
require_once __DIR__ . "/../clases/Reparacion.php";

class TecnicoController {
    private $model;

    public function __construct() {
        $this->model = new ReparacionesModel();
    }

    public function index() {
        // Verificar que sea un técnico
        if ($_SESSION["usuario_cargo"] !== "Tecnico") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        // Obtener información del técnico logueado
        $tecnico_id = $_SESSION["usuario_id"];
        $tecnico_nombre = $_SESSION["usuario_nombre"];
        
        // Obtener estadísticas del técnico
        $estadisticas = $this->model->obtenerEstadisticasTecnico($tecnico_id);
        
        // Obtener reparaciones recientes (últimas 5)
        $reparacionesRecientes = $this->model->obtenerReparacionesRecientes($tecnico_id, 5);
        
        // Obtener todas las reparaciones para la tabla completa
        $todasReparaciones = $this->model->obtenerPorTecnico($tecnico_id);
        
        require "views/viewsTecnico/index.php";
    }

    public function actualizar() {
        // Verificar que sea un técnico
        if ($_SESSION["usuario_cargo"] !== "Tecnico") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        if ($_POST) {
            try {
                $id_reparacion = $_POST['id_reparacion'] ?? null;
                $nuevo_estado = $_POST['estado'] ?? null;
                $diagnostico = $_POST['diagnostico'] ?? null;
                $costo = $_POST['costo'] ?? null;

                if ($id_reparacion && $nuevo_estado) {
                    // Obtener la reparación existente con información completa
                    $reparacion = $this->model->obtenerReparacionCompleta($id_reparacion);
                    
                    if ($reparacion && $reparacion->tecnico_id == $_SESSION["usuario_id"]) {
                        // Actualizar los campos
                        $reparacion->setEstado($nuevo_estado);
                        
                        // Solo actualizar diagnóstico si se proporcionó
                        if (!empty($diagnostico)) {
                            $reparacion->setDiagnostico($diagnostico);
                        }
                        
                        // Solo actualizar costo si se proporcionó y es válido
                        if (!empty($costo) && is_numeric($costo)) {
                            $reparacion->setCosto($costo);
                        }
                        
                        // Si se marca como listo/completado, agregar fecha de entrega
                        if ($nuevo_estado === 'Listo' || $nuevo_estado === 'Entregado') {
                            $fecha_entrega = date('Y-m-d H:i:s');
                            $reparacion->setFechaEntrega($fecha_entrega);
                        }
                        
                        $resultado = $this->model->actualizar($reparacion);
                        
                        // Mensaje de éxito
                        if ($resultado) {
                            $_SESSION['mensaje'] = 'Reparación actualizada correctamente a estado: ' . $nuevo_estado;
                        } else {
                            $_SESSION['error'] = 'No se pudo actualizar la reparación.';
                        }
                    } else {
                        $_SESSION['error'] = 'No se encontró la reparación o no tienes permisos para editarla.';
                    }
                } else {
                    $_SESSION['error'] = 'Datos incompletos para actualizar la reparación.';
                }
            } catch (Exception $e) {
                // Manejo de errores
                $_SESSION['error'] = 'Error al actualizar la reparación: ' . $e->getMessage();
            }
            
            header("Location: index.php?page=tecnico");
            exit;
        }
    }

    public function ver() {
        // Verificar que sea un técnico
        if ($_SESSION["usuario_cargo"] !== "Tecnico") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $reparacion = $this->model->obtenerReparacionCompleta($id);
            require "views/viewsTecnico/ver.php";
        } else {
            header("Location: index.php?page=tecnico");
            exit;
        }
    }

    public function editar() {
        // Verificar que sea un técnico
        if ($_SESSION["usuario_cargo"] !== "Tecnico") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?page=tecnico");
            exit;
        }

        // Usar el método que obtiene la reparación con información completa
        $reparacion = $this->model->obtenerReparacionCompleta($id);
        if (!$reparacion) {
            header("Location: index.php?page=tecnico");
            exit;
        }

        // Verificar que la reparación pertenece al técnico logueado
        if ($reparacion->tecnico_id != $_SESSION["usuario_id"]) {
            header("Location: index.php?page=tecnico");
            exit;
        }

        require "views/viewsTecnico/editar.php";
    }
}