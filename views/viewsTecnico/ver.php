<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Reparación - Panel del Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #212529;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .info-card {
            border-left: 4px solid #007bff;
        }
        .client-card {
            border-left: 4px solid #28a745;
        }
        .device-card {
            border-left: 4px solid #ffc107;
        }
        .repair-card {
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">🔍 Taller de Reparación - Ver Reparación</span>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">👋 Bienvenido, <?= htmlspecialchars($_SESSION["usuario_nombre"] ?? "Técnico") ?></span>
            <a href="index.php?page=tecnico" class="btn btn-outline-light btn-sm me-2">← Volver al Dashboard</a>
            <a href="index.php?page=auth&action=logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if ($reparacion): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary mb-0">📋 Reparación #<?= $reparacion->getId() ?></h2>
                    <div>
                        <?php
                        $estado = $reparacion->getEstado();
                        $badgeClass = '';
                        $icon = '';
                        switch($estado) {
                            case 'Pendiente':
                                $badgeClass = 'bg-warning text-dark';
                                $icon = '⏳';
                                break;
                            case 'En Reparacion':
                                $badgeClass = 'bg-info';
                                $icon = '🔧';
                                break;
                            case 'Listo':
                                $badgeClass = 'bg-success';
                                $icon = '✅';
                                break;
                            case 'Entregado':
                                $badgeClass = 'bg-secondary';
                                $icon = '📦';
                                break;
                            default:
                                $badgeClass = 'bg-light text-dark';
                                $icon = '❓';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?> status-badge">
                            <?= $icon ?> <?= htmlspecialchars($estado) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información del Cliente -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm client-card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-person"></i> Información del Cliente</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="detail-label">Nombre:</span>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-value">
                                    <?= htmlspecialchars($reparacion->cliente_nombre ?? 'No disponible') ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="detail-label">Técnico Asignado:</span>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-value">
                                    <?= htmlspecialchars($reparacion->tecnico_nombre ?? 'No asignado') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Dispositivo -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm device-card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-phone"></i> Información del Dispositivo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="detail-label">Marca:</span>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-value">
                                    <?= htmlspecialchars($reparacion->celular_marca ?? 'No disponible') ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="detail-label">Modelo:</span>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-value">
                                    <?= htmlspecialchars($reparacion->celular_modelo ?? 'No disponible') ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <span class="detail-label">IMEI:</span>
                            </div>
                            <div class="col-sm-8">
                                <span class="detail-value">
                                    <code><?= htmlspecialchars($reparacion->imei ?? 'No disponible') ?></code>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Detalles de la Reparación -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm repair-card">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-tools"></i> Detalles de la Reparación</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <span class="detail-label">ID Diagnóstico:</span>
                            </div>
                            <div class="col-sm-9">
                                <span class="detail-value">
                                    #<?= htmlspecialchars($reparacion->getIdDiagnostico()) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <span class="detail-label">Diagnóstico:</span>
                            </div>
                            <div class="col-sm-9">
                                <div class="alert alert-light border-0">
                                    <?= nl2br(htmlspecialchars($reparacion->getDiagnostico() ?? 'Sin diagnóstico disponible')) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <span class="detail-label">Costo:</span>
                            </div>
                            <div class="col-sm-9">
                                <span class="detail-value h5 text-success">
                                    <?php if ($reparacion->getCosto()): ?>
                                        💰 $<?= number_format($reparacion->getCosto(), 2) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin costo definido</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fechas e Información Adicional -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm info-card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-calendar"></i> Fechas</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="detail-label">Fecha de Ingreso:</span><br>
                            <span class="detail-value">
                                <i class="bi bi-calendar-check text-primary"></i>
                                <?= $reparacion->getFechaIngreso() ? date('d/m/Y H:i', strtotime($reparacion->getFechaIngreso())) : 'No disponible' ?>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <span class="detail-label">Fecha de Entrega:</span><br>
                            <span class="detail-value">
                                <?php if ($reparacion->getFechaEntrega()): ?>
                                    <i class="bi bi-calendar-check text-success"></i>
                                    <?= date('d/m/Y H:i', strtotime($reparacion->getFechaEntrega())) ?>
                                <?php else: ?>
                                    <i class="bi bi-calendar-x text-muted"></i>
                                    <span class="text-muted">Pendiente</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <?php if ($reparacion->getFechaIngreso() && $reparacion->getFechaEntrega()): ?>
                            <?php 
                            $inicio = new DateTime($reparacion->getFechaIngreso());
                            $fin = new DateTime($reparacion->getFechaEntrega());
                            $diferencia = $inicio->diff($fin);
                            ?>
                            <div class="mb-3">
                                <span class="detail-label">Tiempo de Reparación:</span><br>
                                <span class="detail-value">
                                    <i class="bi bi-clock text-info"></i>
                                    <?= $diferencia->days ?> días, <?= $diferencia->h ?> horas
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">Acciones Disponibles</h6>
                        <div class="btn-group" role="group">
                            <a href="index.php?page=tecnico&action=editar&id=<?= $reparacion->getId() ?>" 
                               class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Editar Reparación
                            </a>
                            
                            <a href="index.php?page=tecnico" 
                               class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver al Dashboard
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="btn btn-info">
                                <i class="bi bi-printer"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- No se encontró la reparación -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 text-warning">Reparación no encontrada</h4>
                        <p class="text-muted">La reparación solicitada no existe o no tienes permisos para verla.</p>
                        <a href="index.php?page=tecnico" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style media="print">
    .btn, .navbar, .card-header {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    body {
        background: white !important;
    }
</style>

</body>
</html>