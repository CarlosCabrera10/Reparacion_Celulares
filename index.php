<?php
session_start();
require_once "config/rutas.php";
define("URL", "http://192.168.1.160/Reparacion_Celulares/");

// Verifica si está logueado
if (!isset($_SESSION["usuario_id"]) && ($_GET["page"] ?? "auth") !== "auth" && ($_GET["page"] ?? "auth") !== "reparacion" && ($_GET["page"] ?? "auth") !== "reparaciones" && ($_GET["page"] ?? "auth") !== "tecnico") {
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

// Controlador
$archivo = Contenido::obtenerContenido($page);
require_once $archivo;

// Fix for ReparacionController class name mismatch
if ($page === "reparacion") {
    $controllerName = "ReparacionesController";
} else {
    $controllerName = ucfirst($page) . "Controller";
}

$controller = new $controllerName();

$action = $_GET["action"] ?? "index";

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    require "views/404.php";
}
