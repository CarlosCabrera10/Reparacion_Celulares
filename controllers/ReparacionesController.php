<?php
require_once __DIR__ . "/../models/ReparacionesModel.php";
require_once __DIR__ . "/../models/DiagnosticosModel.php";
require_once __DIR__ . "/../models/CelularesModel.php";
require_once __DIR__ . "/../models/ClientesModel.php";
require_once __DIR__ . "/../models/EmpleadoModel.php";
require_once __DIR__ . "/../clases/Reparacion.php";

class ReparacionesController {
    private $model;

    public function __construct() {
        $this->model = new ReparacionesModel();
    }

    public function index() {
        $reparaciones = $this->model->obtenerTodos();
        include __DIR__ . "/../views/empleados/reparaciones.php";
    }

    public function crear() {

        echo "Reparaciones crear";
    }

    public function editar() {
        $idReparacion = $_GET['id'] ?? null;
        if (!$idReparacion) {
            die("ID de reparación no proporcionado");
        }

        $reparacion = $this->model->obtenerPorId($idReparacion);
        if (!$reparacion) {
            die("Reparación no encontrada");
        }

        // Obtener datos relacionados
        $diagnosticoModel = new DiagnosticosModel();
        $diagnostico = $diagnosticoModel->obtenerPorId($reparacion->getIdDiagnostico());

        $celularModel = new CelularesModel();
        $celular = $celularModel->obtenerPorId($diagnostico->getIdCelular());

        $clienteModel = new ClientesModel();
        $cliente = $clienteModel->obtenerPorId($celular->getIdCliente());

        $empleadoModel = new EmpleadoModel();
        $empleado = $empleadoModel->obtenerPorId($diagnostico->getIdEmpleado());

        // Agregar propiedades adicionales al objeto reparacion para la vista
        $reparacion->cliente_nombre = $cliente ? $cliente->getNombre() : 'N/A';
        $reparacion->celular_marca = $celular ? $celular->getMarca() : 'N/A';
        $reparacion->celular_modelo = $celular ? $celular->getModelo() : 'N/A';
        $reparacion->imei = $celular ? $celular->getImei() : 'N/A';

        // Variables para la vista
        $back_url = "index.php?page=reparaciones&action=index";
        $action_url = "index.php?page=reparaciones&action=actualizar";

        // Pasar datos a la vista
        include __DIR__ . "/../views/viewsTecnico/editar.php";
    }

    public function actualizar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id_reparacion'] ?? null;
            if (!$id) {
                $_SESSION['error'] = "ID de reparación no proporcionado.";
                header("Location: index.php?page=reparaciones&action=index");
                exit;
            }

            $reparacion = $this->model->obtenerPorId($id);
            if (!$reparacion) {
                $_SESSION['error'] = "Reparación no encontrada.";
                header("Location: index.php?page=reparaciones&action=index");
                exit;
            }

            // Actualizar los campos
            $reparacion->setDiagnostico($_POST['diagnostico'] ?? $reparacion->getDiagnostico());
            $reparacion->setEstado($_POST['estado'] ?? $reparacion->getEstado());
            $reparacion->setCosto($_POST['costo'] ?? $reparacion->getCosto());

            // Si el estado es 'Listo' o 'Entregado', actualizar fecha_entrega
            if (in_array($reparacion->getEstado(), ['Listo', 'Entregado'])) {
                $reparacion->setFechaEntrega(date('Y-m-d H:i:s'));
            }

            $this->model->actualizar($reparacion);

            $_SESSION['mensaje'] = "Reparación actualizada exitosamente.";
            header("Location: index.php?page=reparaciones&action=index");
            exit;
        }

        // Si no es POST, redirigir
        header("Location: index.php?page=reparaciones&action=index");
        exit;
    }

    public function eliminar() {
        echo "Reparaciones eliminar";
    }

    public function ver() {
        $idReparacion = $_GET['id'] ?? null;
        if (!$idReparacion) {
            die("ID de reparación no proporcionado");
        }

        $reparacion = $this->model->obtenerPorId($idReparacion);
        if (!$reparacion) {
            die("Reparación no encontrada");
        }

        // Obtener datos relacionados
        $diagnosticoModel = new DiagnosticosModel();
        $diagnostico = $diagnosticoModel->obtenerPorId($reparacion->getIdDiagnostico());

        $celularModel = new CelularesModel();
        $celular = $celularModel->obtenerPorId($diagnostico->getIdCelular());

        $clienteModel = new ClientesModel();
        $cliente = $clienteModel->obtenerPorId($celular->getIdCliente());

        $empleadoModel = new EmpleadoModel();
        $empleado = $empleadoModel->obtenerPorId($diagnostico->getIdEmpleado());

        // Pasar datos a la vista
        include __DIR__ . "/../views/reparacion/ver.php";
    }

    

}
