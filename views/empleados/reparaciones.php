<?php include __DIR__ . "/../layout/menu.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reparaciones - Taller de Reparación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
        }
        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
        }
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        .filtro-activo {
            border: 2px solid #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .font-monospace {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.8rem;
        }
        .diagnostico-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid mt-4">
    <!-- Mensajes de éxito/error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Encabezado con gradiente -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-gradient-custom text-white rounded" style="padding: 2rem;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold mb-2">
                        <i class="bi bi-wrench-adjustable-circle"></i> Gestión de Reparaciones
                    </h1>
                    <p class="mb-0 opacity-75">Panel de reparaciones</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wrench-adjustable-circle-fill display-4 mb-3"></i>
                    <h3 class="mb-0"><?= count($reparaciones); ?></h3>
                    <p class="mb-0">Total Reparaciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-warning card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $pendientes = array_filter($reparaciones, function($r) {
                                return $r->getEstado() === 'Pendiente' || $r->getEstado() === 'En Reparacion';
                            });
                            echo count($pendientes);
                        ?>
                    </h3>
                    <p class="mb-0">En Proceso</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-success card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $completadas = array_filter($reparaciones, function($r) {
                                return $r->getEstado() === 'Listo' || $r->getEstado() === 'Entregado';
                            });
                            echo count($completadas);
                        ?>
                    </h3>
                    <p class="mb-0">Completadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-info card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-currency-dollar display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $totalIngresos = array_sum(array_map(function($r) {
                                return $r->getCosto() ?? 0;
                            }, array_filter($reparaciones, function($r) {
                                return $r->getEstado() === 'Entregado';
                            })));
                            echo '$' . number_format($totalIngresos, 2);
                        ?>
                    </h3>
                    <p class="mb-0">Ingresos Totales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de reparaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Lista de Reparaciones</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 350px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por cliente, técnico o modelo...">
                        </div>
                        <select id="estadoFilter" class="form-select" style="width: 160px;">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Reparacion">En Reparación</option>
                            <option value="Listo">Listo</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                        <button id="limpiarFiltros" class="btn btn-outline-light btn-sm" title="Limpiar filtros">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Mostrando <span id="contadorResultados"><?= count($reparaciones) ?></span> de <?= count($reparaciones) ?> reparaciones
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-funnel"></i> Usa los filtros para buscar reparaciones específicas
                            </small>
                        </div>
                    </div>

                    <?php if (!empty($reparaciones)): ?>
                        <div class="table-responsive">
                            <table id="reparacionesTable" class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> ID</th>
                                        <th><i class="bi bi-person"></i> Cliente</th>
                                        <th><i class="bi bi-phone"></i> Dispositivo</th>
                                        <th><i class="bi bi-shield-check"></i> IMEI</th>
                                        <th><i class="bi bi-person-badge"></i> Técnico</th>
                                        <th><i class="bi bi-calendar"></i> Ingreso</th>
                                        <th><i class="bi bi-calendar-check"></i> Entrega</th>
                                        <th><i class="bi bi-clipboard-check"></i> Estado</th>
                                        <th><i class="bi bi-file-text"></i> Diagnóstico</th>
                                        <th><i class="bi bi-currency-dollar"></i> Costo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reparaciones as $r): ?>
                                        <tr data-cliente="<?= htmlspecialchars(strtolower($r->cliente_nombre)) ?>"
                                            data-tecnico="<?= htmlspecialchars(strtolower($r->tecnico_nombre)) ?>"
                                            data-modelo="<?= htmlspecialchars(strtolower($r->celular_modelo)) ?>"
                                            data-marca="<?= htmlspecialchars(strtolower($r->celular_marca)) ?>"
                                            data-estado="<?= htmlspecialchars($r->getEstado()) ?>">
                                            <td class="fw-bold text-primary">#<?= $r->getId(); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($r->cliente_nombre); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= htmlspecialchars($r->celular_marca); ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($r->celular_modelo); ?></small>
                                                </div>
                                            </td>
                                            <td><small class="font-monospace text-muted"><?= htmlspecialchars($r->imei); ?></small></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-tools text-white" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($r->tecnico_nombre); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <small>
                                                    <?= date('d/m/Y', strtotime($r->getFechaIngreso())); ?>
                                                    <br><span class="text-muted"><?= date('H:i', strtotime($r->getFechaIngreso())); ?></span>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($r->getFechaEntrega()): ?>
                                                    <small>
                                                        <?= date('d/m/Y', strtotime($r->getFechaEntrega())); ?>
                                                        <br><span class="text-muted"><?= date('H:i', strtotime($r->getFechaEntrega())); ?></span>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?=
                                                    $r->getEstado() === 'Pendiente' ? 'secondary' :
                                                    ($r->getEstado() === 'En Reparacion' ? 'warning' :
                                                    ($r->getEstado() === 'Listo' ? 'success' : 
                                                    ($r->getEstado() === 'Entregado' ? 'primary' : 'secondary')))
                                                ?> fs-6">
                                                    <?= $r->getEstado(); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="diagnostico-cell" data-bs-toggle="tooltip" 
                                                     title="<?= htmlspecialchars($r->getDiagnostico() ?? 'Sin diagnóstico') ?>">
                                                    <?= htmlspecialchars($r->getDiagnostico() ?? '—') ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($r->getCosto()): ?>
                                                    <strong class="text-success">$<?= number_format($r->getCosto(), 2); ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje cuando no hay resultados -->
                        <div id="sinResultados" class="alert alert-warning d-none">
                            <i class="bi bi-search"></i> 
                            No se encontraron reparaciones que coincidan con los filtros aplicados.
                            <button id="limpiarFiltros2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar filtros
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay reparaciones registradas. 
                            <a href="index.php?page=reparaciones&action=crear" class="alert-link">¡Registra la primera!</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
    <small>&copy; <?= date('Y') ?> Taller de Reparación de Celulares</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Elementos del DOM
    const searchInput = document.getElementById('searchInput');
    const estadoFilter = document.getElementById('estadoFilter');
    const limpiarFiltros = document.getElementById('limpiarFiltros');
    const limpiarFiltros2 = document.getElementById('limpiarFiltros2');
    const table = document.getElementById('reparacionesTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const sinResultados = document.getElementById('sinResultados');
    const contadorResultados = document.getElementById('contadorResultados');
    
    if (!tbody) return;
    
    const filas = Array.from(tbody.querySelectorAll('tr'));
    const totalReparaciones = filas.length;
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        const textoFiltro = searchInput.value.toLowerCase().trim();
        const estadoFiltro = estadoFilter.value;
        let filasVisibles = 0;
        
        filas.forEach(fila => {
            const cliente = fila.dataset.cliente || '';
            const tecnico = fila.dataset.tecnico || '';
            const modelo = fila.dataset.modelo || '';
            const marca = fila.dataset.marca || '';
            const estado = fila.dataset.estado || '';
            
            // Filtro por texto
            const coincideTexto = textoFiltro === '' || 
                cliente.includes(textoFiltro) || 
                tecnico.includes(textoFiltro) ||
                modelo.includes(textoFiltro) ||
                marca.includes(textoFiltro);
            
            // Filtro por estado
            const coincideEstado = estadoFiltro === '' || estado === estadoFiltro;
            
            // Mostrar/ocultar fila
            if (coincideTexto && coincideEstado) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Actualizar contador
        contadorResultados.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && (textoFiltro !== '' || estadoFiltro !== '')) {
            sinResultados.classList.remove('d-none');
        } else {
            sinResultados.classList.add('d-none');
        }
        
        // Aplicar estilo a campos con filtros activos
        if (textoFiltro !== '') {
            searchInput.classList.add('filtro-activo');
        } else {
            searchInput.classList.remove('filtro-activo');
        }
        
        if (estadoFiltro !== '') {
            estadoFilter.classList.add('filtro-activo');
        } else {
            estadoFilter.classList.remove('filtro-activo');
        }
    }
    
    // Función para limpiar filtros
    function limpiarTodosFiltros() {
        searchInput.value = '';
        estadoFilter.value = '';
        searchInput.classList.remove('filtro-activo');
        estadoFilter.classList.remove('filtro-activo');
        aplicarFiltros();
        searchInput.focus();
    }
    
    // Event listeners
    let timeoutId;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(aplicarFiltros, 300);
    });
    
    estadoFilter.addEventListener('change', aplicarFiltros);
    limpiarFiltros.addEventListener('click', limpiarTodosFiltros);
    if (limpiarFiltros2) {
        limpiarFiltros2.addEventListener('click', limpiarTodosFiltros);
    }
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl + F para enfocar búsqueda
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        // Escape para limpiar filtros
        if (e.key === 'Escape') {
            limpiarTodosFiltros();
        }
    });
    
    // Placeholder dinámico
    const placeholders = [
        'Buscar por cliente...',
        'Buscar por técnico...',
        'Buscar por modelo...',
        'Buscar por marca...',
        'Buscar por cliente, técnico o modelo...'
    ];
    
    let placeholderIndex = 0;
    setInterval(() => {
        if (document.activeElement !== searchInput) {
            searchInput.placeholder = placeholders[placeholderIndex];
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
        }
    }, 3000);
    
    searchInput.addEventListener('focus', function() {
        this.placeholder = 'Buscar por cliente, técnico o modelo... (Ctrl+F)';
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.placeholder = 'Buscar por cliente, técnico o modelo...';
        }
    });
    
    console.log('✅ Sistema de filtros de reparaciones inicializado');
    console.log(`🔧 Total reparaciones: ${totalReparaciones}`);
});
</script>

</body>
</html>