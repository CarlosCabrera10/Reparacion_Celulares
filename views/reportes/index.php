<?php
require_once __DIR__ . "/../layout/menu.php";


// Asegúrate de que $reparacionesMes, $reparacionesTecnico y $reparacionesMarca
// vienen desde tu ReportesController
?>

<div class="container mt-5">
    <h1 class="h3 mb-4">Reportes de Reparaciones</h1>

    <div class="row">
        <!-- Reparaciones por mes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Reparaciones por mes</h5>
                    <canvas id="graficoMes"></canvas>
                    <a href="index.php?page=reportes&action=generarMes" class="btn btn-primary mt-2">Generar PDF</a>
                    <a href="index.php?page=reportes&action=generarMesExcel" class="btn btn-success mt-2">Descargar Excel</a>
                </div>
            </div>
        </div>

        <!-- Reparaciones por técnico -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Reparaciones por técnico</h5>
                    <canvas id="graficoTecnico"></canvas>
                    <a href="index.php?page=reportes&action=generarTecnico" class="btn btn-primary mt-2">Generar PDF</a>
                    <a href="index.php?page=reportes&action=generarTecnicoExcel" class="btn btn-success mt-2">Descargar Excel</a>
                </div>
            </div>
        </div>

        <!-- Reparaciones por marca -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Reparaciones por marca</h5>
                    <canvas id="graficoMarca"></canvas>
                    <a href="index.php?page=reportes&action=generarMarca" class="btn btn-primary mt-2">Generar PDF</a>
                    <a href="index.php?page=reportes&action=generarMarcaExcel" class="btn btn-success mt-2">Descargar Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const reparacionesMes = <?= json_encode($reparacionesMes) ?>;
const reparacionesTecnico = <?= json_encode($reparacionesTecnico) ?>;
const reparacionesMarca = <?= json_encode($reparacionesMarca) ?>;

// Reparaciones por mes 
new Chart(document.getElementById('graficoMes'), {
    type: 'bar',
    data: {
        labels: reparacionesMes.map(r => r.mes),
        datasets: [
            { label: 'Pendientes', data: reparacionesMes.map(r => r.pendientes), backgroundColor: 'rgba(255,99,132,0.6)' },
            { label: 'En reparación', data: reparacionesMes.map(r => r.en_reparacion), backgroundColor: 'rgba(54,162,235,0.6)' },
            { label: 'Listos', data: reparacionesMes.map(r => r.listos), backgroundColor: 'rgba(255,206,86,0.6)' },
            { label: 'Entregados', data: reparacionesMes.map(r => r.entregados), backgroundColor: 'rgba(75,192,192,0.6)' }
        ]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Reparaciones por mes' } },
        scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
    }
});

//  Reparaciones por técnico 
new Chart(document.getElementById('graficoTecnico'), {
    data: {
        labels: reparacionesTecnico.map(r => r.tecnico),
        datasets: [
            { type: 'bar', label: 'Total reparaciones', data: reparacionesTecnico.map(r => r.total), backgroundColor: 'rgba(153,102,255,0.6)' },
            { type: 'line', label: 'Tiempo promedio (días)', data: reparacionesTecnico.map(r => parseFloat(r.tiempo_promedio)), borderColor: 'rgba(255,159,64,1)', yAxisID: 'y1', fill: false, tension: 0.2 }
        ]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Reparaciones por técnico' } },
        scales: {
            y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Cantidad de reparaciones' } },
            y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Tiempo promedio (días)' } }
        }
    }
});

// Reparaciones por marca 
new Chart(document.getElementById('graficoMarca'), {
    type: 'pie',
    data: {
        labels: reparacionesMarca.map(r => r.marca),
        datasets: [{ data: reparacionesMarca.map(r => r.total), backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40'] }]
    },
    options: { responsive: true, plugins: { title: { display: true, text: 'Reparaciones por marca' } } }
});
</script>
