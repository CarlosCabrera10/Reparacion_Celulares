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
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validaciones server-side: todos los campos requeridos y IMEI obligatorio con 15 dígitos
            $id_cliente = $_POST["id_cliente"] ?? null;
            $marca = trim($_POST["marca"] ?? '');
            $modelo = trim($_POST["modelo"] ?? '');
            $imei = trim($_POST["imei"] ?? '');

            if (empty($id_cliente)) {
                $errors[] = "Seleccione un cliente.";
            }
            if ($marca === '') {
                $errors[] = "La marca es requerida.";
            }
            if ($modelo === '') {
                $errors[] = "El modelo es requerido.";
            }
            // IMEI ahora obligatorio y debe tener exactamente 15 dígitos
            if ($imei === '') {
                $errors[] = "El IMEI es obligatorio y debe contener 15 dígitos.";
            } elseif (!preg_match('/^\d{15}$/', $imei)) {
                $errors[] = "IMEI inválido. Debe contener exactamente 15 números.";
            }

            if (count($errors) === 0) {
                $celular = new Celular(
                    null,
                    $id_cliente,
                    $marca,
                    $modelo,
                    $imei
                );

                $result = $this->model->crear($celular);
                if ($result && is_numeric($result)) {
                    // Redirigir al flujo de diagnóstico pasando el id del celular creado
                    header("Location: index.php?page=diagnosticos&action=crear&celular_id=" . $result);
                    exit;
                } else {
                    $errors[] = "Error al guardar el celular.";
                }
            }
            // Si hay errores, el form se volverá a mostrar con $errors
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
