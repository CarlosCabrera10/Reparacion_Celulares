<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reparación - Panel del Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">🔧 Taller de Reparación - Editar Reparación</span>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">👋 Bienvenido, <?= htmlspecialchars($_SESSION["usuario_nombre"] ?? "Técnico") ?></span>
            <a href="index.php?page=tecnico" class="btn btn-outline-light btn-sm me-2">← Volver</a>
            <a href="index.php?page=auth&action=logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Actualizar Reparación #<?= $reparacion->getId() ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['mensaje']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=tecnico&action=actualizar">
                        <input type="hidden" name="id_reparacion" value="<?= $reparacion->getId() ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Cliente:</strong></label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($reparacion->cliente_nombre ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Celular:</strong></label>
                                <p class="form-control-plaintext"><?= htmlspecialchars(($reparacion->celular_marca ?? '') . ' ' . ($reparacion->celular_modelo ?? '')) ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Fecha de Ingreso:</strong></label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($reparacion->getFechaIngreso()) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>IMEI:</strong></label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($reparacion->imei ?? 'N/A') ?></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diagnostico" class="form-label"><strong>Diagnóstico Técnico:</strong></label>
                            <textarea class="form-control" id="diagnostico" name="diagnostico" rows="4" 
                                      placeholder="Describe el diagnóstico técnico detallado..."><?= htmlspecialchars($reparacion->getDiagnostico() ?? '') ?></textarea>
                            <div class="form-text">Detalla el problema encontrado y la solución aplicada.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="estado" class="form-label"><strong>Estado:</strong> <span class="text-danger">*</span></label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Pendiente" <?= $reparacion->getEstado() === 'Pendiente' ? 'selected' : '' ?>>⏳ Pendiente</option>
                                    <option value="En Reparacion" <?= $reparacion->getEstado() === 'En Reparacion' ? 'selected' : '' ?>>🔧 En Reparación</option>
                                    <option value="Listo" <?= $reparacion->getEstado() === 'Listo' ? 'selected' : '' ?>>✅ Listo (Completada)</option>
                                    <option value="Entregado" <?= $reparacion->getEstado() === 'Entregado' ? 'selected' : '' ?>>📋 Entregado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="costo" class="form-label"><strong>Costo Estimado ($):</strong></label>
                                <input type="number" class="form-control" id="costo" name="costo" 
                                       step="0.01" min="0" value="<?= htmlspecialchars($reparacion->getCosto() ?? '') ?>"
                                       placeholder="0.00">
                                <div class="form-text">Costo final de la reparación (opcional hasta completar).</div>
                            </div>
                        </div>

                        <?php if ($reparacion->getEstado() === 'Listo' || $reparacion->getEstado() === 'Entregado'): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Esta reparación ya está completada/entregada. Los cambios serán limitados.
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?page=tecnico" class="btn btn-secondary me-md-2">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
    <small>&copy; <?= date('Y') ?> Taller de Reparación de Celulares</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>