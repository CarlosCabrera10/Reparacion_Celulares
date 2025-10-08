<?php
// Obtener el nombre del técnico desde la sesión
$nombreTecnico = $_SESSION["usuario_nombre"] ?? "Técnico";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard del Técnico - Taller de Reparación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
        }
        .stat-card-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
        }
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
        .avatar-xs {
            width: 25px;
            height: 25px;
            font-size: 10px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        #tablaHistorial th {
            font-weight: 600;
            font-size: 0.85rem;
            border-bottom: 2px solid #dee2e6;
        }
        .table-dark th {
            background-color: #343a40 !important;
            border-color: #454d55 !important;
        }
        .font-monospace {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.8rem;
        }
        .filtro-activo {
            border: 2px solid #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-custom">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-tools"></i> Dashboard del Técnico</span>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> Bienvenido, <?= htmlspecialchars($nombreTecnico) ?>
            </span>
            <a href="index.php?page=auth&action=logout" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
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
    
    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wrench-adjustable-circle display-4 mb-3"></i>
                    <h3 class="mb-0"><?= $estadisticas['total'] ?></h3>
                    <p class="mb-0">Total Reparaciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-success card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-3"></i>
                    <h3 class="mb-0"><?= $estadisticas['completadas_semana'] ?></h3>
                    <p class="mb-0">Completadas esta Semana</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-warning card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-month display-4 mb-3"></i>
                    <h3 class="mb-0"><?= $estadisticas['este_mes'] ?></h3>
                    <p class="mb-0">Reparaciones este Mes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-info card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 mb-3"></i>
                    <h3 class="mb-0"><?= $estadisticas['promedio_dias'] ?></h3>
                    <p class="mb-0">Días Promedio</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de estadísticas -->
    <div class="row mb-4">
        
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-currency-dollar"></i> Ingresos Generados</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <h2 class="text-success mb-2">$<?= number_format($estadisticas['total_generado'], 2) ?></h2>
                        <p class="text-muted">Total completadas</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-speedometer2"></i> Rendimiento</h6>
                </div>
                <div class="card-body">
                    <?php 
                    // Procesar estadísticas por estado para las barras de progreso
                    $estadosPorDefecto = ['Pendiente' => 0, 'En Reparacion' => 0, 'Listo' => 0, 'Entregado' => 0];
                    foreach ($estadisticas['por_estado'] as $estado) {
                        $estadosPorDefecto[$estado['estado']] = $estado['cantidad'];
                    }
                    $completadas = $estadosPorDefecto['Listo'] + $estadosPorDefecto['Entregado'];
                    ?>
                    <div class="mb-3">
                        <small class="text-muted">Pendientes</small>
                        <div class="progress">
                            <div class="progress-bar bg-secondary" style="width: <?= $estadisticas['total'] > 0 ? ($estadosPorDefecto['Pendiente'] / $estadisticas['total']) * 100 : 0 ?>%"></div>
                        </div>
                        <small><?= $estadosPorDefecto['Pendiente'] ?> de <?= $estadisticas['total'] ?></small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">En Reparación</small>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: <?= $estadisticas['total'] > 0 ? ($estadosPorDefecto['En Reparacion'] / $estadisticas['total']) * 100 : 0 ?>%"></div>
                        </div>
                        <small><?= $estadosPorDefecto['En Reparacion'] ?> de <?= $estadisticas['total'] ?></small>
                    </div>
                    <div>
                        <small class="text-muted">Completadas</small>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?= $estadisticas['total'] > 0 ? ($completadas / $estadisticas['total']) * 100 : 0 ?>%"></div>
                        </div>
                        <small><?= $completadas ?> de <?= $estadisticas['total'] ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reparaciones Recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Reparaciones Recientes</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="filtroRecientes" class="form-control form-control-sm" placeholder="Buscar en recientes...">
                        </div>
                        <select id="filtroEstadoRecientes" class="form-select form-select-sm" style="width: 130px;">
                            <option value="">Todos</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Reparacion">En Reparación</option>
                            <option value="Listo">Listo</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                        <button id="limpiarFiltrosRecientes" class="btn btn-outline-light btn-sm" title="Limpiar filtros">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> 
                                Mostrando <span id="contadorRecientes"><?= count($reparacionesRecientes) ?></span> de <?= count($reparacionesRecientes) ?> reparaciones recientes
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Últimas reparaciones asignadas
                            </small>
                        </div>
                    </div>
                    <?php if (empty($reparacionesRecientes)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No tienes reparaciones asignadas por el momento.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> #</th>
                                        <th><i class="bi bi-person"></i> Cliente</th>
                                        <th><i class="bi bi-phone"></i> Celular</th>
                                        <th><i class="bi bi-shield-check"></i> IMEI</th>
                                        <th><i class="bi bi-clipboard-check"></i> Estado</th>
                                        <th><i class="bi bi-currency-dollar"></i> Costo</th>
                                        <th><i class="bi bi-tools"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaRecientesBody">
                                    <?php foreach ($reparacionesRecientes as $r): ?>
                                        <tr data-cliente="<?= htmlspecialchars(strtolower($r->cliente_nombre ?? '')) ?>" 
                                            data-marca="<?= htmlspecialchars(strtolower($r->celular_marca ?? '')) ?>" 
                                            data-modelo="<?= htmlspecialchars(strtolower($r->celular_modelo ?? '')) ?>"
                                            data-estado="<?= htmlspecialchars($r->getEstado()) ?>"
                                            data-imei="<?= htmlspecialchars(strtolower($r->imei ?? '')) ?>">
                                            <td class="fw-bold text-primary">#<?= $r->getId() ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-person text-white" style="font-size: 0.7rem;"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($r->cliente_nombre ?? 'N/A') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= htmlspecialchars($r->celular_marca ?? 'N/A') ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($r->celular_modelo ?? 'N/A') ?></small>
                                                </div>
                                            </td>
                                            <td><small class="font-monospace text-muted"><?= htmlspecialchars($r->imei ?? 'N/A') ?></small></td>
                                            <td>
                                                <span class="badge bg-<?=
                                                    $r->getEstado() === 'Pendiente' ? 'secondary' :
                                                    ($r->getEstado() === 'En Reparacion' ? 'warning' :
                                                    ($r->getEstado() === 'Listo' ? 'success' : 
                                                    ($r->getEstado() === 'Entregado' ? 'primary' : 'secondary')))
                                                ?>">
                                                    <?= $r->getEstado() ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold text-success">
                                                <?= $r->getCosto() ? '$' . number_format($r->getCosto(), 2) : '<span class="text-muted">—</span>' ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?page=tecnico&action=ver&id=<?= $r->getId() ?>" 
                                                       class="btn btn-outline-info" data-bs-toggle="tooltip" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if ($r->getEstado() !== 'Listo' && $r->getEstado() !== 'Entregado'): ?>
                                                        <a href="index.php?page=tecnico&action=editar&id=<?= $r->getId() ?>" 
                                                           class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje cuando no hay resultados en reparaciones recientes -->
                        <div id="sinResultadosRecientes" class="alert alert-warning d-none">
                            <i class="bi bi-search"></i> 
                            No se encontraron reparaciones recientes que coincidan con los filtros.
                            <button id="limpiarFiltrosRecientes2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar filtros
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Completa (Colapsable) -->
    <div class="collapse mt-4" id="allReparaciones">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Todas las Reparaciones</h5>
            </div>
            <div class="card-body">
                <?php if (empty($todasReparaciones)): ?>
                    <div class="alert alert-info">No tienes reparaciones asignadas.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Celular</th>
                                    <th>IMEI</th>
                                    <th>Diagnóstico</th>
                                    <th>Estado</th>
                                    <th>Costo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($todasReparaciones as $r): ?>
                                    <tr>
                                        <td><?= $r->getId() ?></td>
                                        <td><?= htmlspecialchars($r->cliente_nombre ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars(($r->celular_marca ?? '') . ' ' . ($r->celular_modelo ?? '')) ?></td>
                                        <td><?= htmlspecialchars($r->imei ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($r->getDiagnostico() ?? '—') ?></td>
                                        <td>
                                            <span class="badge bg-<?=
                                                $r->getEstado() === 'Pendiente' ? 'secondary' :
                                                ($r->getEstado() === 'En Reparacion' ? 'warning' :
                                                ($r->getEstado() === 'Listo' ? 'success' : 
                                                ($r->getEstado() === 'Entregado' ? 'primary' : 'secondary')))
                                            ?>">
                                                <?= $r->getEstado() ?>
                                            </span>
                                        </td>
                                        <td><?= $r->getCosto() ? '$' . number_format($r->getCosto(), 2) : '—' ?></td>
                                        <td>
                                            <a href="index.php?page=tecnico&action=ver&id=<?= $r->getId() ?>" 
                                               class="btn btn-sm btn-info me-1">👁️ Ver</a>
                                            <?php if ($r->getEstado() !== 'Listo' && $r->getEstado() !== 'Entregado'): ?>
                                                <a href="index.php?page=tecnico&action=editar&id=<?= $r->getId() ?>" 
                                                   class="btn btn-sm btn-primary">✏️ Actualizar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Historial Completo de Reparaciones -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial Completo de Reparaciones</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por cliente, marca o modelo...">
                        </div>
                        <select id="filtroEstado" class="form-select" style="width: 150px;">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Reparacion">En Reparación</option>
                            <option value="Listo">Listo</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                        <button id="limpiarFiltros" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Mostrando <span id="contadorResultados"><?= count($todasReparaciones) ?></span> de <?= count($todasReparaciones) ?> reparaciones
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-funnel"></i> Usa los filtros para encontrar reparaciones específicas
                            </small>
                        </div>
                    </div>

                    <?php if (empty($todasReparaciones)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No tienes reparaciones en tu historial.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="tablaHistorial" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> ID</th>
                                        <th><i class="bi bi-person"></i> Cliente</th>
                                        <th><i class="bi bi-phone"></i> Dispositivo</th>
                                        <th><i class="bi bi-shield-check"></i> IMEI</th>
                                        <th><i class="bi bi-calendar"></i> Fecha Ingreso</th>
                                        <th><i class="bi bi-calendar-check"></i> Fecha Entrega</th>
                                        <th><i class="bi bi-clipboard-check"></i> Estado</th>
                                        <th><i class="bi bi-file-text"></i> Diagnóstico Inicial</th>
                                        <th><i class="bi bi-file-text"></i> Diagnóstico</th>
                                        <th><i class="bi bi-currency-dollar"></i> Costo</th>
                                        <th><i class="bi bi-tools"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaHistorialBody">
                                    <?php foreach ($todasReparaciones as $r): ?>
                                        <tr data-cliente="<?= htmlspecialchars(strtolower($r->cliente_nombre ?? '')) ?>" 
                                            data-marca="<?= htmlspecialchars(strtolower($r->celular_marca ?? '')) ?>" 
                                            data-modelo="<?= htmlspecialchars(strtolower($r->celular_modelo ?? '')) ?>"
                                            data-estado="<?= htmlspecialchars($r->getEstado()) ?>"
                                            data-imei="<?= htmlspecialchars(strtolower($r->imei ?? '')) ?>">
                                            <td class="fw-bold text-primary">#<?= $r->getId() ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($r->cliente_nombre ?? 'N/A') ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= htmlspecialchars($r->celular_marca ?? 'N/A') ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($r->celular_modelo ?? 'N/A') ?></small>
                                                </div>
                                            </td>
                                            <td><small class="font-monospace"><?= htmlspecialchars($r->imei ?? 'N/A') ?></small></td>
                                            <td>
                                                <small>
                                                    <?= date('d/m/Y', strtotime($r->getFechaIngreso())) ?>
                                                    <br><span class="text-muted"><?= date('H:i', strtotime($r->getFechaIngreso())) ?></span>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($r->getFechaEntrega()): ?>
                                                    <small>
                                                        <?= date('d/m/Y', strtotime($r->getFechaEntrega())) ?>
                                                        <br><span class="text-muted"><?= date('H:i', strtotime($r->getFechaEntrega())) ?></span>
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
                                                    <?= $r->getEstado() ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                    <?php if (!empty($r->descripcion)): ?>
                                                        <span data-bs-toggle="tooltip" title="<?= htmlspecialchars($r->descripcion) ?>">
                                                            <?= htmlspecialchars(substr($r->descripcion, 0, 50)) ?><?= strlen($r->descripcion) > 50 ? '...' : '' ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin diagnóstico</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>


                                            <td>
                                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                    <?php if ($r->getDiagnostico()): ?>
                                                        <span data-bs-toggle="tooltip" title="<?= htmlspecialchars($r->getDiagnostico()) ?>">
                                                            <?= htmlspecialchars(substr($r->getDiagnostico(), 0, 50)) ?><?= strlen($r->getDiagnostico()) > 50 ? '...' : '' ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin diagnóstico</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($r->getCosto()): ?>
                                                    <strong class="text-success">$<?= number_format($r->getCosto(), 2) ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?page=tecnico&action=ver&id=<?= $r->getId() ?>" 
                                                       class="btn btn-outline-info" data-bs-toggle="tooltip" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if ($r->getEstado() !== 'Listo' && $r->getEstado() !== 'Entregado'): ?>
                                                        <a href="index.php?page=tecnico&action=editar&id=<?= $r->getId() ?>" 
                                                           class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                            <i class="bi bi-pencil"></i>
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
                            No se encontraron reparaciones que coincidan con los filtros aplicados.
                            <button id="limpiarFiltros2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar filtros
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
    <small>&copy; <?= date('Y') ?> Taller de Reparación de Celulares - Dashboard del Técnico</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM para reparaciones recientes
    const filtroRecientes = document.getElementById('filtroRecientes');
    const filtroEstadoRecientes = document.getElementById('filtroEstadoRecientes');
    const limpiarFiltrosRecientes = document.getElementById('limpiarFiltrosRecientes');
    const limpiarFiltrosRecientes2 = document.getElementById('limpiarFiltrosRecientes2');
    const tablaRecientesBody = document.getElementById('tablaRecientesBody');
    const sinResultadosRecientes = document.getElementById('sinResultadosRecientes');
    const contadorRecientes = document.getElementById('contadorRecientes');
    
    // Elementos del DOM para historial completo
    const filtroNombre = document.getElementById('filtroNombre');
    const filtroEstado = document.getElementById('filtroEstado');
    const limpiarFiltros = document.getElementById('limpiarFiltros');
    const limpiarFiltros2 = document.getElementById('limpiarFiltros2');
    const tablaBody = document.getElementById('tablaHistorialBody');
    const sinResultados = document.getElementById('sinResultados');
    const contadorResultados = document.getElementById('contadorResultados');
    
    // Todas las filas de ambas tablas
    const filasRecientes = tablaRecientesBody ? Array.from(tablaRecientesBody.querySelectorAll('tr')) : [];
    const todasLasFilas = tablaBody ? Array.from(tablaBody.querySelectorAll('tr')) : [];
    const totalRecientes = filasRecientes.length;
    const totalReparaciones = todasLasFilas.length;
    
    console.log('🔍 Sistema de filtros - Estado inicial:');
    console.log('- filtroRecientes encontrado:', !!filtroRecientes);
    console.log('- filtroEstadoRecientes encontrado:', !!filtroEstadoRecientes);
    console.log('- tablaRecientesBody encontrado:', !!tablaRecientesBody);
    console.log('- filasRecientes encontradas:', filasRecientes.length);
    console.log('- filtroNombre encontrado:', !!filtroNombre);
    console.log('- filtroEstado encontrado:', !!filtroEstado);
    console.log('- tablaHistorialBody encontrado:', !!tablaBody);
    console.log('- filasHistorial encontradas:', todasLasFilas.length);
    
    // Test: agregar event listener simple
    if (filtroRecientes) {
        console.log('🔧 Agregando test listener simple...');
        filtroRecientes.addEventListener('keyup', function() {
            console.log('🔥 TEST: Tecla presionada en filtroRecientes:', this.value);
        });
    }
    
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Función para aplicar filtros a reparaciones recientes
    function aplicarFiltrosRecientes() {
        console.log('🔍 Ejecutando aplicarFiltrosRecientes');
        if (!tablaRecientesBody) {
            console.warn('⚠️ tablaRecientesBody no encontrada');
            return;
        }
        
        const textoFiltro = filtroRecientes.value.toLowerCase().trim();
        const estadoFiltro = filtroEstadoRecientes.value;
        
        console.log('📊 Filtros aplicados:', { textoFiltro, estadoFiltro });
        
        let filasVisibles = 0;
        
        filasRecientes.forEach(fila => {
            const cliente = fila.dataset.cliente || '';
            const marca = fila.dataset.marca || '';
            const modelo = fila.dataset.modelo || '';
            const imei = fila.dataset.imei || '';
            const estado = fila.dataset.estado || '';
            
            // Filtro por texto (busca en cliente, marca, modelo, IMEI)
            const coincideTexto = textoFiltro === '' || 
                cliente.includes(textoFiltro) || 
                marca.includes(textoFiltro) || 
                modelo.includes(textoFiltro) ||
                imei.includes(textoFiltro);
            
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
        
        // Actualizar contador de resultados
        contadorRecientes.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && (textoFiltro !== '' || estadoFiltro !== '')) {
            sinResultadosRecientes.classList.remove('d-none');
        } else {
            sinResultadosRecientes.classList.add('d-none');
        }
        
        // Aplicar estilo a campos con filtros activos
        if (textoFiltro !== '') {
            filtroRecientes.classList.add('filtro-activo');
        } else {
            filtroRecientes.classList.remove('filtro-activo');
        }
        
        if (estadoFiltro !== '') {
            filtroEstadoRecientes.classList.add('filtro-activo');
        } else {
            filtroEstadoRecientes.classList.remove('filtro-activo');
        }
        
        console.log(`✅ Filtros aplicados. Filas visibles: ${filasVisibles}/${filasRecientes.length}`);
    }
    
    // Función para limpiar filtros de recientes
    function limpiarFiltrosRecientesFn() {
        if (!filtroRecientes) return;
        
        filtroRecientes.value = '';
        filtroEstadoRecientes.value = '';
        filtroRecientes.classList.remove('filtro-activo');
        filtroEstadoRecientes.classList.remove('filtro-activo');
        aplicarFiltrosRecientes();
        filtroRecientes.focus();
    }
    
    // Event listeners para filtros de reparaciones recientes
    if (filtroRecientes) {
        console.log('✅ Configurando filtros para reparaciones recientes');
        filtroRecientes.addEventListener('input', function() {
            console.log('🔍 Input detectado en filtroRecientes:', this.value);
            clearTimeout(timeoutIdRecientes);
            timeoutIdRecientes = setTimeout(aplicarFiltrosRecientes, 200);
        });
        filtroEstadoRecientes.addEventListener('change', function() {
            console.log('🔄 Cambio detectado en filtroEstadoRecientes:', this.value);
            aplicarFiltrosRecientes();
        });
        limpiarFiltrosRecientes.addEventListener('click', limpiarFiltrosRecientesFn);
        if (limpiarFiltrosRecientes2) {
            limpiarFiltrosRecientes2.addEventListener('click', limpiarFiltrosRecientesFn);
        }
        console.log('✅ Event listeners de recientes configurados');
    } else {
        console.warn('⚠️ No se encontró filtroRecientes');
    }
    
    // Función para aplicar filtros al historial completo
    function aplicarFiltros() {
        if (!tablaBody) return;
        
        const textoFiltro = filtroNombre.value.toLowerCase().trim();
        const estadoFiltro = filtroEstado.value;
        
        let filasVisibles = 0;
        
        todasLasFilas.forEach(fila => {
            const cliente = fila.dataset.cliente || '';
            const marca = fila.dataset.marca || '';
            const modelo = fila.dataset.modelo || '';
            const imei = fila.dataset.imei || '';
            const estado = fila.dataset.estado || '';
            
            // Filtro por texto (busca en cliente, marca, modelo, IMEI)
            const coincideTexto = textoFiltro === '' || 
                cliente.includes(textoFiltro) || 
                marca.includes(textoFiltro) || 
                modelo.includes(textoFiltro) ||
                imei.includes(textoFiltro);
            
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
        
        // Actualizar contador de resultados
        contadorResultados.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && (textoFiltro !== '' || estadoFiltro !== '')) {
            sinResultados.classList.remove('d-none');
        } else {
            sinResultados.classList.add('d-none');
        }
        
        // Aplicar estilo a campos con filtros activos
        if (textoFiltro !== '') {
            filtroNombre.classList.add('filtro-activo');
        } else {
            filtroNombre.classList.remove('filtro-activo');
        }
        
        if (estadoFiltro !== '') {
            filtroEstado.classList.add('filtro-activo');
        } else {
            filtroEstado.classList.remove('filtro-activo');
        }
    }
    
    // Función para limpiar filtros del historial
    function limpiarTodosFiltros() {
        if (!filtroNombre) return;
        
        filtroNombre.value = '';
        filtroEstado.value = '';
        filtroNombre.classList.remove('filtro-activo');
        filtroEstado.classList.remove('filtro-activo');
        aplicarFiltros();
        filtroNombre.focus();
    }
    
    // Event listeners para filtros del historial completo
    if (filtroNombre) {
        filtroNombre.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(aplicarFiltros, 300);
        });
        filtroEstado.addEventListener('change', aplicarFiltros);
        limpiarFiltros.addEventListener('click', limpiarTodosFiltros);
        if (limpiarFiltros2) {
            limpiarFiltros2.addEventListener('click', limpiarTodosFiltros);
        }
    }
    
    // Búsqueda en tiempo real con debounce - REMOVIDO (conflicto con event listeners principales)
    let timeoutId;
    let timeoutIdRecientes;
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl + F para enfocar el campo de búsqueda del historial
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            if (filtroNombre) {
                filtroNombre.focus();
                filtroNombre.select();
            }
        }
        
        // Ctrl + R para enfocar el campo de búsqueda de recientes
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            if (filtroRecientes) {
                filtroRecientes.focus();
                filtroRecientes.select();
            }
        }
        
        // Escape para limpiar filtros
        if (e.key === 'Escape') {
            limpiarTodosFiltros();
            limpiarFiltrosRecientesFn();
        }
    });
    
    // Placeholder dinámico para el campo de búsqueda del historial
    if (filtroNombre) {
        const placeholders = [
            'Buscar por cliente...',
            'Buscar por marca...',
            'Buscar por modelo...',
            'Buscar por IMEI...',
            'Buscar por cliente, marca o modelo...'
        ];
        
        let placeholderIndex = 0;
        setInterval(() => {
            if (document.activeElement !== filtroNombre) {
                filtroNombre.placeholder = placeholders[placeholderIndex];
                placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            }
        }, 3000);
        
        // Mensaje de ayuda cuando se hace focus
        filtroNombre.addEventListener('focus', function() {
            this.placeholder = 'Buscar por cliente, marca, modelo o IMEI... (Ctrl+F)';
        });
        
        filtroNombre.addEventListener('blur', function() {
            if (this.value === '') {
                this.placeholder = 'Buscar por cliente, marca o modelo...';
            }
        });
    }
    
    // Configurar placeholder para filtro de recientes
    if (filtroRecientes) {
        filtroRecientes.addEventListener('focus', function() {
            this.placeholder = 'Buscar en recientes... (Ctrl+R)';
        });
        
        filtroRecientes.addEventListener('blur', function() {
            if (this.value === '') {
                this.placeholder = 'Buscar en recientes...';
            }
        });
    }
    
    console.log('🔍 Sistema de filtros inicializado');
    console.log(`📊 Reparaciones recientes: ${totalRecientes}`);
    console.log(`📋 Total historial: ${totalReparaciones}`);
});
</script>

</body>
</html>
