<?php
class Contenido {
    public static $contenido = [
        "empleados" => "controllers/EmpleadosController.php",
        "auth" => "controllers/AuthController.php",
        "clientes" => "controllers/ClientesController.php",
        "celulares" => "controllers/CelularesController.php",
        "diagnosticos" => "controllers/DiagnosticosController.php",
        "reparaciones" => "controllers/ReparacionesController.php",
        "tickets" => "controllers/TicketsController.php",
        "reportes" => "controllers/ReportesController.php",
        "tecnico" => "controllers/TecnicoController.php"

    ];

    public static function obtenerContenido($clave) {
        $vista = self::$contenido[$clave] ?? null;
        return $vista ?: "views/404.php";
    }
}
