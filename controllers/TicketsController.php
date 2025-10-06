<?php
require_once __DIR__ . "/../models/TicketsModel.php";
require_once __DIR__ . "/../models/ReparacionesModel.php";
require_once __DIR__ . "/../models/DiagnosticosModel.php";
require_once __DIR__ . "/../models/CelularesModel.php";
require_once __DIR__ . "/../models/ClientesModel.php";
require_once __DIR__ . "/../models/EmpleadoModel.php";
require_once __DIR__ . "/../clases/Ticket.php";
require_once __DIR__ . "/../clases/Reparacion.php";
require_once __DIR__ . "/../clases/Diagnostico.php";
require_once __DIR__ . "/../clases/Celular.php";
require_once __DIR__ . "/../clases/Cliente.php";
require_once __DIR__ . "/../clases/Empleado.php";

// TCPDF
require_once __DIR__ . "/../vendor/autoload.php";

class TicketsController {
    private $ticketsModel;
    private $reparacionesModel;
    private $diagnosticosModel;
    private $celularesModel;
    private $clientesModel;
    private $empleadosModel;

    public function __construct() {
        $this->ticketsModel = new TicketsModel();
        $this->reparacionesModel = new ReparacionesModel();
        $this->diagnosticosModel = new DiagnosticosModel();
        $this->celularesModel = new CelularesModel();
        $this->clientesModel = new ClientesModel();
        $this->empleadosModel = new EmpleadoModel();
    }

    public function generar() {
        $idTicket = $_GET['id'] ?? null;
        if (!$idTicket) {
            die("ID de ticket no proporcionado");
        }

        // Traer ticket
        $ticket = $this->ticketsModel->obtenerPorId($idTicket);
        if (!$ticket) {
            die("Ticket no encontrado");
        }

        // Traer reparación
        $reparacion = $this->reparacionesModel->obtenerPorId($ticket->getIdReparacion());

        // Traer diagnóstico
        $diagnostico = $this->diagnosticosModel->obtenerPorId($reparacion->getIdDiagnostico());

        // Traer celular
        $celular = $this->celularesModel->obtenerPorId($diagnostico->getIdCelular());

        // Traer cliente
        $cliente = $this->clientesModel->obtenerPorId($celular->getIdCliente());

        // Traer técnico
        $empleado = $this->empleadosModel->obtenerPorId($diagnostico->getIdEmpleado());

        // Crear PDF
        $pdf = new TCPDF('P', 'mm', array(80, 200), true, 'UTF-8', false);
        $pdf->SetCreator('Reparación de Celulares');
        $pdf->SetAuthor('Reparación');
        $pdf->SetTitle('Ticket de Reparación');
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(true, 5);
        $pdf->AddPage();

        // Contenido del ticket
        $html = '
        <h2 style="text-align:center;">TICKET DE REPARACIÓN</h2>
        <hr>
        <p><strong>Código Ticket:</strong> ' . $ticket->getCodigoTicket() . '</p>
        <p><strong>Cliente:</strong> ' . htmlspecialchars($cliente->getNombre()) . '</p>
        <p><strong>Celular:</strong> ' . htmlspecialchars($celular->getMarca() . ' - ' . $celular->getModelo()) . '</p>
        <p><strong>IMEI:</strong> ' . htmlspecialchars($celular->getImei()) . '</p>
        <p><strong>Técnico:</strong> ' . htmlspecialchars($empleado->getNombre()) . '</p>
        <p><strong>Fecha de Diagnóstico:</strong> ' . $diagnostico->getFechaDiagnostico() . '</p>
        <p><strong>Descripción:</strong> ' . htmlspecialchars($diagnostico->getDescripcion()) . '</p>
        <hr>
        <p><strong>Estado Reparación:</strong> ' . htmlspecialchars($reparacion->getEstado()) . '</p>
        <p><strong>Fecha Ingreso:</strong> ' . $reparacion->getFechaIngreso() . '</p>
        <p><strong>Fecha Entrega:</strong> ' . $reparacion->getFechaEntrega() . '</p>
        <p><strong>Costo:</strong> $' . number_format($reparacion->getCosto(), 2) . '</p>
        <hr>
        <p style="text-align:center;">Gracias por su preferencia</p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Agregar QR Code con URL para ver detalles de la reparación
        $url = URL . "index.php?page=reparacion&action=ver&id=" . $reparacion->getId();
        $pdf->write2DBarcode($url, 'QRCODE,H', 30, $pdf->GetY() + 5, 20, 20, $style = array(), 'N');
        $pdf->Output('ticket_'.$ticket->getCodigoTicket().'.pdf', 'I'); // 'I' para mostrar en navegador
    }

    public function show() {
        $idTicket = $_GET['id'] ?? null;
        if (!$idTicket) {
            die("ID de ticket no proporcionado");
        }

        $ticket = $this->ticketsModel->obtenerPorId($idTicket);
        if (!$ticket) {
            die("Ticket no encontrado");
        }

        $reparacion = $this->reparacionesModel->obtenerPorId($ticket->getIdReparacion());
        $diagnostico = $this->diagnosticosModel->obtenerPorId($reparacion->getIdDiagnostico());
        $celular = $this->celularesModel->obtenerPorId($diagnostico->getIdCelular());
        $cliente = $this->clientesModel->obtenerPorId($celular->getIdCliente());
        $empleado = $this->empleadosModel->obtenerPorId($diagnostico->getIdEmpleado());

        include __DIR__ . "/../views/tickets/detail.php";
}
}