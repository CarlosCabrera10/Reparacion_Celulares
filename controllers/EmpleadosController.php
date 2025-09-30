<?php
require_once __DIR__ . "/../models/EmpleadoModel.php";
require_once __DIR__ . "/../clases/Empleado.php";

class EmpleadosController {
    private $model;

    public function __construct() {
        $this->model = new EmpleadoModel();
    }

    public function index() {
        $empleados = $this->model->obtenerTodos();

        include __DIR__ . "/../views/empleados/index.php";
    }

    public function crear() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $empleado = new Empleado(
                null,
                $_POST["nombre"],
                $_POST["cargo"],
                $_POST["usuario"],
                $passwordHash
            );

            $this->model->crear($empleado);
            header("Location: index.php?page=empleados&action=index");
            exit;
        }

        include __DIR__ . "/../views/empleados/create.php";
    }


    public function editar() {
        $id = $_GET["id"] ?? null;

        if (!$id) {
            header("Location: index.php?page=empleados&action=index");
            exit;
        }

        // Obtener el empleado actual
        $empleado = $this->model->obtenerPorId($id);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Si el usuario cambia la contraseña, se hace hash
            $passwordHash = !empty($_POST["password"]) 
                ? password_hash($_POST["password"], PASSWORD_DEFAULT)
                : $empleado->getPassword(); // si está vacío, mantiene la contraseña actual

            // Crear objeto Empleado actualizado
            $empleadoActualizado = new Empleado(
                $id,
                $_POST["nombre"],
                $_POST["cargo"],
                $_POST["usuario"],
                $passwordHash
            );

            // Actualizar en la base de datos
            $this->model->actualizar($empleadoActualizado);

            header("Location: index.php?page=empleados&action=index");
            exit;
        }

        // Preparar datos para la vista
        $empleadoData = [
            "id" => $empleado->getId(),
            "nombre" => $empleado->getNombre(),
            "cargo" => $empleado->getCargo(),
            "usuario" => $empleado->getUsuario()
        ];

        include __DIR__ . "/../views/empleados/edit.php";
    }



    public function eliminar() {
        $id = $_GET["id"] ?? null;
        if ($id) {
            $this->model->eliminar($id);
        }
            header("Location: index.php?page=empleados&action=index");
        exit;
    }
}
