<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <h1 class="h3 mb-4">Lista de Reparaciones</h1>

    <?php if (!empty($reparaciones)): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ID Diagnóstico</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Entrega</th>
                            <th>Estado</th>
                            <th>Diagnóstico</th>
                            <th>Costo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reparaciones as $r): ?>
                            <tr>
                                <td><?= $r->getId(); ?></td>
                                <td><?= $r->getIdDiagnostico(); ?></td>
                                <td><?= htmlspecialchars($r->getFechaIngreso()); ?></td>
                                <td><?= htmlspecialchars($r->getFechaEntrega() ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($r->getEstado()); ?></td>
                                <td><?= htmlspecialchars($r->getDiagnostico() ?? 'N/A'); ?></td>
                                <td>$<?= number_format($r->getCosto() ?? 0, 2); ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <p>No hay reparaciones registradas.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
