<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <?php if (isset($_SESSION["usuario_nombre"])): ?>
        <div class="alert alert-info shadow-sm">
            👋 Bienvenida/o, <strong><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></strong>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Lista de Clientes</h1>
        <a href="index.php?page=clientes&action=crear" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo cliente
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o teléfono...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="clientesTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)): ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><?= $c->getId(); ?></td>
                                <td class="nombre"><?= htmlspecialchars($c->getNombre()); ?></td>
                                <td class="telefono"><?= htmlspecialchars($c->getTelefono()); ?></td>
                                <td><?= htmlspecialchars($c->getEmail()); ?></td>
                                <td class="text-center">
                                    <a href="index.php?page=clientes&action=editar&id=<?= $c->getId(); ?>" 
                                       class="btn btn-warning btn-sm me-1">✏️ Editar</a>
                                   
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay clientes registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('clientesTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    searchInput.addEventListener('input', () => {
        const val = searchInput.value.toLowerCase();
        for (let row of rows) {
            const nombre = row.querySelector('.nombre').textContent.toLowerCase();
            const telefono = row.querySelector('.telefono').textContent.toLowerCase();
            row.style.display = (nombre.includes(val) || telefono.includes(val)) ? '' : 'none';
        }
    });
</script>
