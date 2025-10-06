<?php
require_once __DIR__ . "/../models/ReparacionesModel.php";
require_once __DIR__ . "/../clases/Reparacion.php";

class ReparacionesController {
    private $model;

    public function __construct() {
        $this->model = new ReparacionesModel();
    }

    // 📋 Panel principal de reparaciones (para admin y recepcionista)
    public function index() {
        // Verificar sesión de administrador o recepcionista
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["usuario_cargo"]) || 
            ($_SESSION["usuario_cargo"] !== "Administrador" && $_SESSION["usuario_cargo"] !== "Recepcionista")) {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $reparaciones = $this->model->obtenerTodas();
        require "views/reparaciones/index.php";
    }

    // 👁️ Ver detalles de una reparación
    public function ver() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["usuario_cargo"]) || 
            ($_SESSION["usuario_cargo"] !== "Administrador" && $_SESSION["usuario_cargo"] !== "Recepcionista")) {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $reparacion = $this->model->obtenerReparacionCompleta($id);
            require "views/reparaciones/ver.php";
        } else {
            header("Location: index.php?page=reparaciones");
            exit;
        }
    }

    // ✏️ Editar reparación (solo administradores pueden editar)
    public function editar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Solo administradores pueden editar reparaciones
        if (!isset($_SESSION["usuario_cargo"]) || $_SESSION["usuario_cargo"] !== "Administrador") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $id = $_GET["id"] ?? null;
        if (!$id) {
            header("Location: index.php?page=reparaciones");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $reparacion = new Reparacion(
                $id,
                $_POST["id_diagnostico"],
                $_POST["fecha_ingreso"],
                $_POST["fecha_entrega"] ?: null,
                $_POST["estado"],
                $_POST["diagnostico"],
                $_POST["costo"] ?: null
            );

            if ($this->model->actualizar($reparacion)) {
                $_SESSION['mensaje'] = "Reparación actualizada exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar la reparación";
            }
            
            header("Location: index.php?page=reparaciones");
            exit;
        }

        $reparacion = $this->model->obtenerReparacionCompleta($id);
        require "views/reparaciones/editar.php";
    }

    // ➕ Crear nueva reparación (admin y recepcionista)
    public function crear() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["usuario_cargo"]) || 
            ($_SESSION["usuario_cargo"] !== "Administrador" && $_SESSION["usuario_cargo"] !== "Recepcionista")) {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $reparacion = new Reparacion(
                null,
                $_POST["id_diagnostico"],
                $_POST["fecha_ingreso"],
                $_POST["fecha_entrega"] ?: null,
                $_POST["estado"],
                $_POST["diagnostico"],
                $_POST["costo"] ?: null
            );

            if ($this->model->crear($reparacion)) {
                $_SESSION['mensaje'] = "Reparación creada exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear la reparación";
            }
            
            header("Location: index.php?page=reparaciones");
            exit;
        }

        // Obtener datos necesarios para el formulario
        require_once __DIR__ . "/../models/DiagnosticosModel.php";
        $diagnosticosModel = new DiagnosticosModel();
        $diagnosticos = $diagnosticosModel->obtenerTodos();
        
        require "views/reparaciones/crear.php";
    }

    // 🗑️ Eliminar reparación (solo administradores)
    public function eliminar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Solo administradores pueden eliminar reparaciones
        if (!isset($_SESSION["usuario_cargo"]) || $_SESSION["usuario_cargo"] !== "Administrador") {
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $id = $_GET["id"] ?? null;
        if ($id) {
            if ($this->model->eliminar($id)) {
                $_SESSION['mensaje'] = "Reparación eliminada exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar la reparación";
            }
        }
        
        header("Location: index.php?page=reparaciones");
        exit;
    }

    // 📊 Dashboard del técnico (método heredado)
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_tecnico = $_SESSION["id_empleado"] ?? 13; // simulado

        $reparaciones = $this->model->obtenerPorEmpleado($id_tecnico);
        include __DIR__ . "/../views/viewsTecnico/dashboard_tecnico.php";
    }
}
