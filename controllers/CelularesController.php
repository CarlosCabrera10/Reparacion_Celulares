<?php
require_once __DIR__ . "/../models/CelularesModel.php";
require_once __DIR__ . "/../models/ClientesModel.php";
require_once __DIR__ . "/../clases/Celular.php";
require_once __DIR__ . "/../clases/Cliente.php";

class CelularesController {
    private $model;
    private $clientesModel;

    public function __construct() {
        $this->model = new CelularesModel();
        $this->clientesModel = new ClientesModel(); 

    }

    public function index() {
        $celulares = $this->model->obtenerTodos();

        $clientesModel = new ClientesModel();
        $clientes = $clientesModel->obtenerTodos(); // array de objetos Cliente

        include __DIR__ . "/../views/celulares/index.php";
    }

    public function crear() {
        $clientes = $this->clientesModel->obtenerTodos(); // <--- obtener todos los clientes

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $celular = new Celular(
                null,
                $_POST["id_cliente"],
                $_POST["marca"],
                $_POST["modelo"],
                $_POST["imei"]
            );

            $this->model->crear($celular);
            header("Location: index.php?page=celulares&action=index");
            exit;
        }

        include __DIR__ . "/../views/celulares/form.php";
    }

    public function editar() {
        $id = $_GET["id"] ?? null;
        $celular = $this->model->obtenerPorId($id);
        $clientes = $this->clientesModel->obtenerTodos(); // <--- cargar clientes

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $celularActualizado = new Celular(
                $id,
                $_POST["id_cliente"],
                $_POST["marca"],
                $_POST["modelo"],
                $_POST["imei"]
            );
            $this->model->actualizar($celularActualizado);
            header("Location: index.php?page=celulares&action=index");
            exit;
        }

        include __DIR__ . "/../views/celulares/form.php";
    }

    public function eliminar() {
        $id = $_GET["id"] ?? null;
        if ($id) $this->model->eliminar($id);
        header("Location: index.php?page=celulares&action=index");
        exit;
    }
}
