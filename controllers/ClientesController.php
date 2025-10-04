<?php
require_once __DIR__ . "/../models/ClientesModel.php";
require_once __DIR__ . "/../clases/Cliente.php";

class ClientesController {
    private $model;

    public function __construct() {
        $this->model = new ClientesModel();
    }

    public function index() {
        $clientes = $this->model->obtenerTodos();
        include __DIR__ . "/../views/clientes/index.php";
    }

    public function crear() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $cliente = new Cliente(
                null,
                $_POST["nombre"],
                $_POST["telefono"],
                $_POST["email"]
            );
            $this->model->crear($cliente);
            header("Location: index.php?page=clientes&action=index");
            exit;
        }
        include __DIR__ . "/../views/clientes/form.php";
    }

    public function editar() {
        $id = $_GET["id"] ?? null;
        if (!$id) { header("Location: index.php?page=clientes&action=index"); exit; }

        $cliente = $this->model->obtenerPorId($id);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $clienteActualizado = new Cliente(
                $id,
                $_POST["nombre"],
                $_POST["telefono"],
                $_POST["email"]
            );
            $this->model->actualizar($clienteActualizado);
            header("Location: index.php?page=clientes&action=index");
            exit;
        }

        include __DIR__ . "/../views/clientes/form.php";
    }

    public function eliminar() {
        $id = $_GET["id"] ?? null;
        if ($id) $this->model->eliminar($id);
        header("Location: index.php?page=clientes&action=index");
        exit;
    }
}
