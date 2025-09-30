<?php
require_once __DIR__ . "/../models/EmpleadoModel.php";

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new EmpleadoModel();
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = $_POST["usuario"] ?? "";
            $password = $_POST["password"] ?? "";

            $empleado = $this->model->obtenerPorUsuario($usuario);

            // Verificar usuario y contraseña primero
            if ($empleado && password_verify($password, $empleado->getPassword())) {
                // Guardar información en sesión
                $_SESSION["usuario_id"] = $empleado->getId();
                $_SESSION["usuario_nombre"] = $empleado->getNombre();
                $_SESSION["usuario_cargo"] = $empleado->getCargo();

                // Redirigir según cargo
                switch ($empleado->getCargo()) {
                    case "Administrador":
                        header("Location: index.php?page=empleados&action=index");
                        exit;
                    case "Recepcionista":
                        header("Location: index.php?page=reparaciones&action=index");
                        exit;
                    case "Tecnico":
                        header("Location: index.php?page=tecnico&action=index");
                        exit;
                    default:
                        session_destroy();
                        $error = "Usuario sin permisos válidos";
                        include __DIR__ . "/../views/auth/login.php";
                        exit;
                }
            } else {
                $error = "Usuario o contraseña incorrectos";
                include __DIR__ . "/../views/auth/login.php";
            }
        } else {
            include __DIR__ . "/../views/auth/login.php";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=auth&action=login");
        exit;
    }
}
