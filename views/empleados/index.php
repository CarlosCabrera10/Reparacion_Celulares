<?php include __DIR__ . "/../layout/menu.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - Taller de Reparación</title>
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
        .avatar-lg {
            width: 45px;
            height: 45px;
            font-size: 18px;
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
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cargo-badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .badge-recepcionista {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .badge-tecnico {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid mt-4">
    <!-- Banner de bienvenida -->
    <?php if (isset($_SESSION["usuario_nombre"])): ?>
        <div class="welcome-banner">
            <div class="d-flex align-items-center">
                <div class="avatar-lg bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3">
                    <i class="bi bi-person-workspace fs-3"></i>
                </div>
                <div>
                    <h5 class="mb-0">👋 Bienvenido/a de nuevo</h5>
                    <p class="mb-0 opacity-75">
                        <strong><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></strong> - Panel de Gestión de Empleados
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

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
                        <i class="bi bi-people-fill"></i> Gestión de Empleados
                    </h1>
                    <p class="mb-0 opacity-75">Administra el equipo de trabajo del taller</p>
                </div>
                <a href="index.php?page=empleados&action=crear" class="btn btn-light btn-lg shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Empleado
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 mb-3"></i>
                    <h3 class="mb-0"><?= count($empleados); ?></h3>
                    <p class="mb-0">Total Empleados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-success card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-tools display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $tecnicos = array_filter($empleados, function($e) {
                                return $e->getCargo() === 'Tecnico';
                            });
                            echo count($tecnicos);
                        ?>
                    </h3>
                    <p class="mb-0">Técnicos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-info card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-check-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $recepcionistas = array_filter($empleados, function($e) {
                                return $e->getCargo() === 'Recepcionista';
                            });
                            echo count($recepcionistas);
                        ?>
                    </h3>
                    <p class="mb-0">Recepcionistas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-warning card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-shield-fill-check display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $administradores = array_filter($empleados, function($e) {
                                return $e->getCargo() === 'Administrador';
                            });
                            echo count($administradores);
                        ?>
                    </h3>
                    <p class="mb-0">Administradores</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de empleados -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Empleados</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o usuario...">
                        </div>
                        <select id="cargoFilter" class="form-select" style="width: 180px;">
                            <option value="">Todos los cargos</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Recepcionista">Recepcionista</option>
                            <option value="Tecnico">Técnico</option>
                        </select>
                        <button id="limpiarFiltros" class="btn btn-outline-light btn-sm" title="Limpiar filtros">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Mostrando <span id="contadorResultados"><?= count($empleados) ?></span> de <?= count($empleados) ?> empleados
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-funnel"></i> Usa los filtros para buscar empleados específicos
                            </small>
                        </div>
                    </div>

                    <?php if (!empty($empleados)): ?>
                        <div class="table-responsive">
                            <table id="empleadosTable" class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> ID</th>
                                        <th><i class="bi bi-person"></i> Nombre</th>
                                        <th><i class="bi bi-person-badge"></i> Usuario</th>
                                        <th><i class="bi bi-briefcase"></i> Cargo</th>
                                        <th><i class="bi bi-activity"></i> Estado</th>
                                        <th class="text-center"><i class="bi bi-tools"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($empleados as $e): ?>
                                        <tr data-nombre="<?= htmlspecialchars(strtolower($e->getNombre())) ?>"
                                            data-usuario="<?= htmlspecialchars(strtolower($e->getUsuario())) ?>"
                                            data-cargo="<?= htmlspecialchars($e->getCargo()) ?>">
                                            <td class="fw-bold text-primary">#<?= $e->getId(); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($e->getNombre()); ?></strong>
                                                        <br><small class="text-muted">ID: <?= $e->getId(); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-at text-muted me-2"></i>
                                                    <span class="font-monospace"><?= htmlspecialchars($e->getUsuario()); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="cargo-badge text-white <?= 
                                                    $e->getCargo() === 'Administrador' ? 'badge-admin' : 
                                                    ($e->getCargo() === 'Recepcionista' ? 'badge-recepcionista' : 'badge-tecnico')
                                                ?>">
                                                    <i class="bi bi-<?= 
                                                        $e->getCargo() === 'Administrador' ? 'shield-fill-check' : 
                                                        ($e->getCargo() === 'Recepcionista' ? 'person-check-fill' : 'tools')
                                                    ?>"></i>
                                                    <?= htmlspecialchars($e->getCargo()); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $e->getActivo() ? 'bg-success' : 'bg-secondary' ?>" id="estadoBadge<?= $e->getId(); ?>">
                                                    <i class="bi <?= $e->getActivo() ? 'bi-check-circle' : 'bi-x-circle' ?>"></i> 
                                                    <?= $e->getActivo() ? 'Activo' : 'Inactivo' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?page=empleados&action=editar&id=<?= $e->getId(); ?>" 
                                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar empleado">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="index.php?page=empleados&action=toggleEstado&id=<?= $e->getId(); ?>" 
                                                    class="btn btn-outline-warning"
                                                    onclick="return confirm('¿Seguro que desea <?= $e->getActivo() ? 'desactivar' : 'activar' ?> este empleado?');"
                                                    title="<?= $e->getActivo() ? 'Desactivar' : 'Activar' ?>">
                                                        <i class="bi <?= $e->getActivo() ? 'bi-person-dash' : 'bi-person-check' ?>"></i>
                                                    </a>
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
                            No se encontraron empleados que coincidan con los filtros aplicados.
                            <button id="limpiarFiltros2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar filtros
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay empleados registrados. 
                            <a href="index.php?page=empleados&action=crear" class="alert-link">¡Registra el primero!</a>
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
    const cargoFilter = document.getElementById('cargoFilter');
    const limpiarFiltros = document.getElementById('limpiarFiltros');
    const limpiarFiltros2 = document.getElementById('limpiarFiltros2');
    const table = document.getElementById('empleadosTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const sinResultados = document.getElementById('sinResultados');
    const contadorResultados = document.getElementById('contadorResultados');
    
    if (!tbody) return;
    
    const filas = Array.from(tbody.querySelectorAll('tr'));
    const totalEmpleados = filas.length;
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        const textoFiltro = searchInput.value.toLowerCase().trim();
        const cargoFiltro = cargoFilter.value;
        let filasVisibles = 0;
        
        filas.forEach(fila => {
            const nombre = fila.dataset.nombre || '';
            const usuario = fila.dataset.usuario || '';
            const cargo = fila.dataset.cargo || '';
            
            // Filtro por texto (busca en nombre y usuario)
            const coincideTexto = textoFiltro === '' || 
                nombre.includes(textoFiltro) || 
                usuario.includes(textoFiltro);
            
            // Filtro por cargo
            const coincideCargo = cargoFiltro === '' || cargo === cargoFiltro;
            
            // Mostrar/ocultar fila
            if (coincideTexto && coincideCargo) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Actualizar contador
        contadorResultados.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && (textoFiltro !== '' || cargoFiltro !== '')) {
            sinResultados.classList.remove('d-none');
        } else {
            sinResultados.classList.add('d-none');
        }
        
        // Aplicar estilo a campos con filtros activos
        if (textoFiltro !== '') {
            searchInput.classList.add('filtro-activo');
        } else {
            searchInput.classList.remove('filtro-activo');
        }
        
        if (cargoFiltro !== '') {
            cargoFilter.classList.add('filtro-activo');
        } else {
            cargoFilter.classList.remove('filtro-activo');
        }
    }
    
    // Función para limpiar filtros
    function limpiarTodosFiltros() {
        searchInput.value = '';
        cargoFilter.value = '';
        searchInput.classList.remove('filtro-activo');
        cargoFilter.classList.remove('filtro-activo');
        aplicarFiltros();
        searchInput.focus();
    }
    
    // Event listeners
    let timeoutId;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(aplicarFiltros, 300);
    });
    
    cargoFilter.addEventListener('change', aplicarFiltros);
    limpiarFiltros.addEventListener('click', limpiarTodosFiltros);
    if (limpiarFiltros2) {
        limpiarFiltros2.addEventListener('click', limpiarTodosFiltros);
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
            limpiarTodosFiltros();
        }
    });
    
    document.querySelectorAll('.toggle-estado-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const activoActual = this.dataset.activo === '1';
        if (!confirm(`¿Deseas ${activoActual ? 'desactivar' : 'activar'} este empleado?`)) return;

        fetch('index.php?page=empleados&action=toggleEstado', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Actualizar badge
                const badge = document.getElementById('estadoBadge' + id);
                badge.className = 'badge ' + (data.activo ? 'bg-success' : 'bg-secondary');
                badge.innerHTML = `<i class="bi ${data.activo ? 'bi-check-circle' : 'bi-x-circle'}"></i> ${data.activo ? 'Activo' : 'Inactivo'}`;
                
                // Actualizar el botón
                this.dataset.activo = data.activo ? '1' : '0';
                this.innerHTML = `<i class="bi ${data.activo ? 'bi-person-dash' : 'bi-person-check'}"></i>`;
                this.title = data.activo ? 'Desactivar' : 'Activar';
            } else {
                alert('No se pudo actualizar el estado del empleado.');
            }
        });
    });
});

    // Placeholder dinámico
    const placeholders = [
        'Buscar por nombre...',
        'Buscar por usuario...',
        'Buscar por nombre o usuario...'
    ];
    
    let placeholderIndex = 0;
    setInterval(() => {
        if (document.activeElement !== searchInput) {
            searchInput.placeholder = placeholders[placeholderIndex];
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
        }
    }, 3000);
    
    searchInput.addEventListener('focus', function() {
        this.placeholder = 'Buscar por nombre o usuario... (Ctrl+F)';
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.placeholder = 'Buscar por nombre o usuario...';
        }
    });
    
    console.log('✅ Sistema de filtros de empleados inicializado');
    console.log(`👥 Total empleados: ${totalEmpleados}`);
});
</script>

</body>
</html>