<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Lista de Diagnósticos</h1>
        <a href="index.php?page=diagnosticos&action=crear" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo diagnóstico
        </a>
    </div>

    <!-- Buscador -->
    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por descripción, celular o empleado...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="diagnosticosTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Celular</th>
                        <th>Tecnico Asignado</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($diagnosticos)): ?>
                        <?php foreach ($diagnosticos as $d): ?>
                            <tr>
                                <td><?= $d->getId() ?></td>
                                <td><?= htmlspecialchars($d->getCelular()->getMarca() . " - " . $d->getCelular()->getModelo()) ?></td>
                                <td><?= htmlspecialchars($d->getEmpleado()->getNombre()) ?></td>
                                <td><?= htmlspecialchars($d->getFechaDiagnostico()) ?></td>
                                <td class="descripcion"><?= htmlspecialchars($d->getDescripcion()) ?></td>
                                <td class="text-center">
                                    <!-- Editar -->
                                    <a href="index.php?page=diagnosticos&action=editar&id=<?= $d->getId() ?>" 
                                       class="btn btn-warning btn-sm me-1">Editar</a>

                                    <!-- Eliminar -->
                                    <a href="index.php?page=diagnosticos&action=eliminar&id=<?= $d->getId() ?>" 
                                       class="btn btn-danger btn-sm me-1" 
                                       onclick="return confirm('¿Seguro que desea eliminar este diagnóstico?')">Eliminar</a>

                                    <!-- Ver Ticket -->
                                    <?php if ($d->getReparacion() && $d->getReparacion()->getTicket()): ?>
                                        <a href="index.php?page=tickets&action=generar&id=<?= $d->getReparacion()->getTicket()->getId() ?>" 
                                           class="btn btn-info btn-sm" target="_blank">🎫 Ver Ticket</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay diagnósticos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Buscador -->
<script>
const searchInput = document.getElementById('searchInput');
const table = document.getElementById('diagnosticosTable');
const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

searchInput.addEventListener('input', () => {
    const val = searchInput.value.toLowerCase();
    for (let row of rows) {
        const descripcion = row.querySelector('.descripcion').textContent.toLowerCase();
        const celular = row.cells[1].textContent.toLowerCase();
        const empleado = row.cells[2].textContent.toLowerCase();
        row.style.display = (descripcion.includes(val) || celular.includes(val) || empleado.includes(val)) ? '' : 'none';
    }
});
</script>
