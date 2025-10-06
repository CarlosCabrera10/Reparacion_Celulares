<?php
session_start();
require_once "config/rutas.php";

// Verifica si está logueado
if (!isset($_SESSION["usuario_id"]) && ($_GET["page"] ?? "auth") !== "auth") {
    header("Location: index.php?page=auth&action=login");
    exit;
}

//  Control de acceso por roles
$cargo = $_SESSION["usuario_cargo"] ?? "";

$page = $_GET["page"] ?? "empleados";

// Bloquear acceso a empleados si no es Administrador
if ($page === "empleados" && $cargo !== "Administrador") {
    header("Location: index.php?page=auth&action=login");
    exit;
}

// Bloquear acceso a tecnico si no es Tecnico
if ($page === "tecnico" && $cargo !== "Tecnico") {
    header("Location: index.php?page=auth&action=login");
    exit;
}

// Controlador
$archivo = Contenido::obtenerContenido($page);
require_once $archivo;

$controllerName = ucfirst($page) . "Controller";
$controller = new $controllerName();

$action = $_GET["action"] ?? "index";

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    require "views/404.php";
}
