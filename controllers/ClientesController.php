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
            $result = $this->model->crear($cliente);
            // Si el modelo devuelve el id del nuevo cliente, mostrar una vista de confirmación
            if ($result && is_numeric($result)) {
                $nuevoClienteId = $result;
                // Obtener datos actualizados del cliente para mostrar en la vista
                $clienteCreado = $this->model->obtenerPorId($nuevoClienteId);
                include __DIR__ . "/../views/clientes/created.php";
                exit;
            }
            // En caso de falla, redirigir al listado (comportamiento previo)
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

    // Endpoint para búsqueda AJAX de clientes (autocomplete)
    public function search() {
        header('Content-Type: application/json; charset=utf-8');
        $q = $_GET['q'] ?? null;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        if ($q === null || trim($q) === '') {
            echo json_encode([]);
            exit;
        }

        $results = $this->model->buscarPorTermino($q, $limit);
        // Devolver array de {id, nombre, telefono}
        $out = [];
        foreach ($results as $c) {
            $out[] = [
                'id' => $c->getId(),
                'nombre' => $c->getNombre(),
                'telefono' => $c->getTelefono(),
                'email' => $c->getEmail()
            ];
        }
        echo json_encode($out);
        exit;
    }
}
