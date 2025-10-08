<?php include __DIR__ . "/../layout/menu.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Diagnósticos - Taller de Reparación</title>
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
        .descripcion-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .badge-date {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.85rem;
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
                        <i class="bi bi-clipboard2-pulse"></i> Gestión de Diagnósticos
                    </h1>
                    <p class="mb-0 opacity-75">Administra los diagnósticos técnicos realizados</p>
                </div>
                <a href="index.php?page=diagnosticos&action=crear" class="btn btn-light btn-lg shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Diagnóstico
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard2-check-fill display-4 mb-3"></i>
                    <h3 class="mb-0"><?= count($diagnosticos); ?></h3>
                    <p class="mb-0">Total Diagnósticos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-success card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $hoy = date('Y-m-d');
                            $diagnosticosHoy = array_filter($diagnosticos, function($d) use ($hoy) {
                                return date('Y-m-d', strtotime($d->getFechaDiagnostico())) === $hoy;
                            });
                            echo count($diagnosticosHoy);
                        ?>
                    </h3>
                    <p class="mb-0">Diagnósticos Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-info card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $tecnicos = array_unique(array_map(function($d) { 
                                return $d->getEmpleado()->getId(); 
                            }, $diagnosticos));
                            echo count($tecnicos);
                        ?>
                    </h3>
                    <p class="mb-0">Técnicos Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-warning card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-ticket-detailed-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $conTicket = array_filter($diagnosticos, function($d) {
                                return $d->getReparacion() && $d->getReparacion()->getTicket();
                            });
                            echo count($conTicket);
                        ?>
                    </h3>
                    <p class="mb-0">Con Ticket</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de diagnósticos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Lista de Diagnósticos</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 350px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por descripción, celular o técnico...">
                        </div>
                        <button id="limpiarFiltro" class="btn btn-outline-light btn-sm" title="Limpiar búsqueda">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Mostrando <span id="contadorResultados"><?= count($diagnosticos) ?></span> de <?= count($diagnosticos) ?> diagnósticos
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-funnel"></i> Usa el buscador para filtrar resultados
                            </small>
                        </div>
                    </div>

                    <?php if (!empty($diagnosticos)): ?>
                        <div class="table-responsive">
                            <table id="diagnosticosTable" class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> ID</th>
                                        <th><i class="bi bi-phone"></i> Dispositivo</th>
                                        <th><i class="bi bi-person-badge"></i> Técnico</th>
                                        <th><i class="bi bi-calendar-event"></i> Fecha</th>
                                        <th><i class="bi bi-file-text"></i> Descripción</th>
                                        <th><i class="bi bi-ticket"></i> Ticket</th>
                                        <th class="text-center"><i class="bi bi-tools"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($diagnosticos as $d): ?>
                                        <tr data-descripcion="<?= htmlspecialchars(strtolower($d->getDescripcion())) ?>"
                                            data-celular="<?= htmlspecialchars(strtolower($d->getCelular()->getMarca() . ' ' . $d->getCelular()->getModelo())) ?>"
                                            data-tecnico="<?= htmlspecialchars(strtolower($d->getEmpleado()->getNombre())) ?>">
                                            <td class="fw-bold text-primary">#<?= $d->getId() ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-phone text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($d->getCelular()->getMarca()) ?></strong>
                                                        <br><small class="text-muted"><?= htmlspecialchars($d->getCelular()->getModelo()) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($d->getEmpleado()->getNombre()) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="badge-date">
                                                    <i class="bi bi-calendar3"></i>
                                                    <?= date('d/m/Y', strtotime($d->getFechaDiagnostico())) ?>
                                                    <br><span class="text-muted"><?= date('H:i', strtotime($d->getFechaDiagnostico())) ?></span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="descripcion-cell" data-bs-toggle="tooltip" 
                                                     title="<?= htmlspecialchars($d->getDescripcion()) ?>">
                                                    <?= htmlspecialchars($d->getDescripcion()) ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($d->getReparacion() && $d->getReparacion()->getTicket()): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Generado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-dash-circle"></i> Sin ticket
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?page=diagnosticos&action=editar&id=<?= $d->getId() ?>" 
                                                       class="btn btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Editar diagnóstico">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($d->getReparacion() && $d->getReparacion()->getTicket()): ?>
                                                        <a href="index.php?page=tickets&action=generar&id=<?= $d->getReparacion()->getTicket()->getId() ?>" 
                                                           class="btn btn-outline-info" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Ver ticket"
                                                           target="_blank">
                                                            <i class="bi bi-ticket-detailed"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje cuando no hay resultados -->
                        <div id="sinResultados" class="alert alert-warning d-none">
                            <i class="bi bi-search"></i> 
                            No se encontraron diagnósticos que coincidan con tu búsqueda.
                            <button id="limpiarFiltro2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar búsqueda
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay diagnósticos registrados. 
                            <a href="index.php?page=diagnosticos&action=crear" class="alert-link">¡Crea el primero!</a>
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
    const limpiarFiltro = document.getElementById('limpiarFiltro');
    const limpiarFiltro2 = document.getElementById('limpiarFiltro2');
    const table = document.getElementById('diagnosticosTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const sinResultados = document.getElementById('sinResultados');
    const contadorResultados = document.getElementById('contadorResultados');
    
    if (!tbody) return;
    
    const filas = Array.from(tbody.querySelectorAll('tr'));
    const totalDiagnosticos = filas.length;
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        const textoFiltro = searchInput.value.toLowerCase().trim();
        let filasVisibles = 0;
        
        filas.forEach(fila => {
            const descripcion = fila.dataset.descripcion || '';
            const celular = fila.dataset.celular || '';
            const tecnico = fila.dataset.tecnico || '';
            
            const coincide = textoFiltro === '' || 
                descripcion.includes(textoFiltro) || 
                celular.includes(textoFiltro) ||
                tecnico.includes(textoFiltro);
            
            if (coincide) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Actualizar contador
        contadorResultados.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && textoFiltro !== '') {
            sinResultados.classList.remove('d-none');
        } else {
            sinResultados.classList.add('d-none');
        }
        
        // Aplicar estilo al campo con filtro activo
        if (textoFiltro !== '') {
            searchInput.classList.add('filtro-activo');
        } else {
            searchInput.classList.remove('filtro-activo');
        }
    }
    
    // Función para limpiar filtros
    function limpiarFiltros() {
        searchInput.value = '';
        searchInput.classList.remove('filtro-activo');
        aplicarFiltros();
        searchInput.focus();
    }
    
    // Event listeners
    let timeoutId;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(aplicarFiltros, 300);
    });
    
    limpiarFiltro.addEventListener('click', limpiarFiltros);
    if (limpiarFiltro2) {
        limpiarFiltro2.addEventListener('click', limpiarFiltros);
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
            limpiarFiltros();
        }
    });
    
    // Placeholder dinámico
    const placeholders = [
        'Buscar por descripción...',
        'Buscar por dispositivo...',
        'Buscar por técnico...',
        'Buscar por descripción, celular o técnico...'
    ];
    
    let placeholderIndex = 0;
    setInterval(() => {
        if (document.activeElement !== searchInput) {
            searchInput.placeholder = placeholders[placeholderIndex];
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
        }
    }, 3000);
    
    searchInput.addEventListener('focus', function() {
        this.placeholder = 'Buscar por descripción, celular o técnico... (Ctrl+F)';
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.placeholder = 'Buscar por descripción, celular o técnico...';
        }
    });
    
    console.log('✅ Sistema de filtros de diagnósticos inicializado');
    console.log(`📋 Total diagnósticos: ${totalDiagnosticos}`);
});
</script>

</body>
</html>