<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Cliente creado</h4>
        </div>
        <div class="card-body">
            <?php if (isset($clienteCreado)): ?>
                <p>El cliente <strong><?= htmlspecialchars($clienteCreado->getNombre()) ?></strong> fue creado correctamente.</p>

                <div class="d-flex gap-2">
                    <!-- Botón para crear celular para este cliente, pasando el id por GET -->
                    <a href="index.php?page=celulares&action=crear&cliente_id=<?= $clienteCreado->getId() ?>" class="btn btn-primary">Crear celular para este cliente</a>

                    <!-- Volver al listado de clientes -->
                    <a href="index.php?page=clientes&action=index" class="btn btn-secondary">Volver a clientes</a>
                </div>
            <?php else: ?>
                <p>No se encontró la información del cliente creado.</p>
                <a href="index.php?page=clientes&action=index" class="btn btn-secondary">Volver a clientes</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
