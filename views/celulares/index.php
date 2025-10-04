<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Lista de Celulares</h1>
        <a href="index.php?page=celulares&action=crear" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo celular
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por marca o modelo...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="celularesTable" class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>IMEI</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($celulares)): ?>
                        <?php foreach ($celulares as $c): ?>
                            <tr>
                                <td><?= $c->getId(); ?></td>
                                <td>
                                <?php 
                                    $clienteNombre = '';
                                    foreach($clientes as $cl) {
                                        if ($cl->getId() == $c->getIdCliente()) {
                                            $clienteNombre = $cl->getNombre();
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($clienteNombre);
                                ?>
                                </td>                            
                                <td class="marca"><?= htmlspecialchars($c->getMarca()); ?></td>
                                <td class="modelo"><?= htmlspecialchars($c->getModelo()); ?></td>
                                <td><?= htmlspecialchars($c->getImei()); ?></td>
                                <td class="text-center">
                                    <a href="index.php?page=celulares&action=editar&id=<?= $c->getId(); ?>" 
                                       class="btn btn-warning btn-sm me-1">✏️ Editar</a>
                                    <a href="index.php?page=celulares&action=eliminar&id=<?= $c->getId(); ?>" 
                                       class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este celular?')">🗑️ Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay celulares registrados</td>
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
    const table = document.getElementById('celularesTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    searchInput.addEventListener('input', () => {
        const val = searchInput.value.toLowerCase();
        for (let row of rows) {
            const marca = row.querySelector('.marca').textContent.toLowerCase();
            const modelo = row.querySelector('.modelo').textContent.toLowerCase();
            row.style.display = (marca.includes(val) || modelo.includes(val)) ? '' : 'none';
        }
    });
</script>
