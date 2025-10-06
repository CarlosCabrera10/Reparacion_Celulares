<?php
require_once __DIR__ . "/../models/DiagnosticosModel.php";
require_once __DIR__ . "/../models/CelularesModel.php";
require_once __DIR__ . "/../models/EmpleadoModel.php";
require_once __DIR__ . "/../clases/Diagnostico.php";
require_once __DIR__ . "/../clases/Celular.php";
require_once __DIR__ . "/../clases/Empleado.php";
require_once __DIR__ . "/../models/TicketsModel.php";
require_once __DIR__ . "/../models/ReparacionesModel.php";
require_once __DIR__ . "/../clases/Ticket.php";
require_once __DIR__ . "/../clases/Reparacion.php";

class DiagnosticosController {
   private $model;
    private $celularesModel;
    private $empleadosModel;
    private $reparacionesModel;
    private $ticketsModel;

    public function __construct() {
        $this->model = new DiagnosticosModel();
        $this->celularesModel = new CelularesModel();
        $this->empleadosModel = new EmpleadoModel();
        $this->reparacionesModel = new ReparacionesModel();
        $this->ticketsModel = new TicketsModel();
    }

    public function index() {
        $diagnosticos = $this->model->obtenerTodos(); // Array de Diagnostico

        // Cargar objetos relacionados
        foreach ($diagnosticos as $d) {
            $celular = $this->celularesModel->obtenerPorId($d->getIdCelular());
            $empleado = $this->empleadosModel->obtenerPorId($d->getIdEmpleado());
            $d->setCelular($celular);
            $d->setEmpleado($empleado);

            // Cargar reparación si existe
            $reparacion = $this->reparacionesModel->obtenerPorDiagnostico($d->getId());
            $d->setReparacion($reparacion);

            if ($reparacion) {
                $ticket = $this->ticketsModel->obtenerPorReparacion($reparacion->getId());
                $reparacion->setTicket($ticket);
            }
        }

        include __DIR__ . "/../views/diagnosticos/index.php";
    }

    public function crear() {
        $celulares = $this->celularesModel->obtenerTodos();
        $empleados = array_filter($this->empleadosModel->obtenerTodos(), function($e) {
            return $e->getCargo() === "Tecnico";
        });

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $diagnostico = new Diagnostico(
                    null,
                    $_POST["id_celular"],
                    $_POST["id_empleado"],
                    $_POST["fecha_diagnostico"],
                    $_POST["descripcion"]
                );


            $id = $this->model->crear($diagnostico);
            $diagnostico->setId($id);

            // Crear reparación automáticamente
            $reparacion = new Reparacion(
                null,
                $diagnostico->getId(),
                date('Y-m-d'), // fecha ingreso
                null,          // fecha entrega
                'Pendiente',
                null,
                null
            );
            $this->reparacionesModel->crear($reparacion);

            // Crear ticket automáticamente
            $codigoTicket = $this->ticketsModel->generarCodigo();
            $ticket = new Ticket(
                null,
                $reparacion->getId(),
                $codigoTicket
            );
            $this->ticketsModel->crear($ticket);

            header("Location: index.php?page=diagnosticos&action=index");
            exit;

        }

        include __DIR__ . "/../views/diagnosticos/form.php";
    }
}

    public function editar() {
        $id = $_GET["id"] ?? null;
        $diagnostico = $this->model->obtenerPorId($id);
        $celulares = $this->celularesModel->obtenerTodos();
        $empleados = array_filter($this->empleadosModel->obtenerTodos(), function($e) {
            return $e->getCargo() === "Tecnico";
        });

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $diagnosticoActualizado = new Diagnostico(
                $id,
                $_POST["id_celular"],
                $_POST["id_empleado"],
                $_POST["fecha_diagnostico"],
                $_POST["descripcion"]
            );
            $this->model->actualizar($diagnosticoActualizado);
            header("Location: index.php?page=diagnosticos&action=index");
            exit;
        }

        include __DIR__ . "/../views/diagnosticos/form.php";
    }

    public function eliminar() {
        $id = $_GET["id"] ?? null;
        if ($id) $this->model->eliminar($id);
        header("Location: index.php?page=diagnosticos&action=index");
        exit;
    }
}
