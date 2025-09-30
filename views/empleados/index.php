<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <?php if (isset($_SESSION["usuario_nombre"])): ?>
        <div class="alert alert-info shadow-sm">
            👋 Bienvenida/o, <strong><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></strong>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Lista de Empleados</h1>
        <a href="index.php?page=empleados&action=crear" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo empleado
        </a>
    </div>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre...">
        </div>
        <div class="col-md-6 mb-2">
            <select id="cargoFilter" class="form-select">
                <option value="">Todos los cargos</option>
                <option value="Administrador">Administrador</option>
                <option value="Recepcionista">Recepcionista</option>
                <option value="Tecnico">Técnico</option>
            </select>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="empleadosTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Cargo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($empleados)): ?>
                        <?php foreach ($empleados as $e): ?>
                            <tr>
                                <td><?= $e->getId(); ?></td>
                                <td class="nombre"><?= htmlspecialchars($e->getNombre()); ?></td>
                                <td><?= htmlspecialchars($e->getUsuario()); ?></td>
                                <td class="cargo"><?= htmlspecialchars($e->getCargo()); ?></td>
                                <td class="text-center">
                                    <a href="index.php?page=empleados&action=editar&id=<?= $e->getId(); ?>" 
                                       class="btn btn-warning btn-sm me-1">✏️ Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay empleados registrados</td>
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

<!-- Buscador y filtro -->
<script>
    const searchInput = document.getElementById('searchInput');
    const cargoFilter = document.getElementById('cargoFilter');
    const table = document.getElementById('empleadosTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const cargoValue = cargoFilter.value;

        for (let row of rows) {
            const nombre = row.querySelector('.nombre').textContent.toLowerCase();
            const cargo = row.querySelector('.cargo').textContent;

            const matchesSearch = nombre.includes(searchValue);
            const matchesCargo = cargoValue === "" || cargo === cargoValue;

            row.style.display = (matchesSearch && matchesCargo) ? "" : "none";
        }
    }

    searchInput.addEventListener('input', filterTable);
    cargoFilter.addEventListener('change', filterTable);
</script>
