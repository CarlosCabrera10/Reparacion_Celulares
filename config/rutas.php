<?php
class Contenido {
    public static $contenido = [
        "empleados" => "controllers/EmpleadosController.php",
        "auth" => "controllers/AuthController.php"
    ];

    public static function obtenerContenido($clave) {
        $vista = self::$contenido[$clave] ?? null;
        return $vista ?: "views/404.php";
    }
}
