<?php
require_once __DIR__ . "/../models/ReportesModel.php";

class ReportesController {
    private $model;

    public function __construct() {
        $this->model = new ReportesModel();
    }

    public function index() {
        // Traer todos los datos para los tres gráficos
        $reparacionesMes = $this->model->reparacionesPorMes();
        $reparacionesTecnico = $this->model->reparacionesPorTecnico();
        $reparacionesMarca = $this->model->reparacionesPorMarca();

        include __DIR__ . "/../views/reportes/index.php";
    }
}
