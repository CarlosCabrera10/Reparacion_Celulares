<?php include __DIR__ . "/../layout/menu.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - Taller de Reparación</title>
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
        .cliente-badge {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #28a745;
            margin-right: 8px;
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
                    <i class="bi bi-person-circle fs-3"></i>
                </div>
                <div>
                    <h5 class="mb-0">👋 Bienvenido/a de nuevo</h5>
                    <p class="mb-0 opacity-75">
                        <strong><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></strong> - Panel de Gestión de Clientes
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
                        <i class="bi bi-people"></i> Gestión de Clientes
                    </h1>
                    <p class="mb-0 opacity-75">Administra la base de datos de tus clientes</p>
                </div>
                <a href="index.php?page=clientes&action=crear" class="btn btn-light btn-lg shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Cliente
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
                    <h3 class="mb-0"><?= count($clientes); ?></h3>
                    <p class="mb-0">Total Clientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-success card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-check-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $conEmail = array_filter($clientes, function($c) {
                                return !empty($c->getEmail());
                            });
                            echo count($conEmail);
                        ?>
                    </h3>
                    <p class="mb-0">Con Email</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-info card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-telephone-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            $conTelefono = array_filter($clientes, function($c) {
                                return !empty($c->getTelefono());
                            });
                            echo count($conTelefono);
                        ?>
                    </h3>
                    <p class="mb-0">Con Teléfono</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card-warning card-hover shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-plus-fill display-4 mb-3"></i>
                    <h3 class="mb-0">
                        <?php 
                            // Asumiendo que tienes una fecha de registro, sino puedes calcular los últimos registrados
                            echo count($clientes) > 0 ? min(5, count($clientes)) : 0;
                        ?>
                    </h3>
                    <p class="mb-0">Nuevos (últimos)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-custom text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Clientes</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 350px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, teléfono o email...">
                        </div>
                        <button id="limpiarFiltro" class="btn btn-outline-light btn-sm" title="Limpiar búsqueda">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Mostrando <span id="contadorResultados"><?= count($clientes) ?></span> de <?= count($clientes) ?> clientes
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-funnel"></i> Usa el buscador para filtrar resultados
                            </small>
                        </div>
                    </div>

                    <?php if (!empty($clientes)): ?>
                        <div class="table-responsive">
                            <table id="clientesTable" class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="bi bi-hash"></i> ID</th>
                                        <th><i class="bi bi-person"></i> Nombre</th>
                                        <th><i class="bi bi-telephone"></i> Teléfono</th>
                                        <th><i class="bi bi-envelope"></i> Email</th>
                                        <th class="text-center"><i class="bi bi-tools"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientes as $c): ?>
                                        <tr data-nombre="<?= htmlspecialchars(strtolower($c->getNombre())) ?>"
                                            data-telefono="<?= htmlspecialchars(strtolower($c->getTelefono())) ?>"
                                            data-email="<?= htmlspecialchars(strtolower($c->getEmail())) ?>">
                                            <td class="fw-bold text-primary">#<?= $c->getId(); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($c->getNombre()); ?></strong>
                                                        <br><small class="text-muted">Cliente ID: <?= $c->getId(); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($c->getTelefono())): ?>
                                                    <a href="tel:<?= htmlspecialchars($c->getTelefono()); ?>" 
                                                       class="text-decoration-none"
                                                       data-bs-toggle="tooltip"
                                                       title="Llamar al cliente">
                                                        <i class="bi bi-telephone-fill text-success me-1"></i>
                                                        <?= htmlspecialchars($c->getTelefono()); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin teléfono</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($c->getEmail())): ?>
                                                    <a href="mailto:<?= htmlspecialchars($c->getEmail()); ?>" 
                                                       class="text-decoration-none"
                                                       data-bs-toggle="tooltip"
                                                       title="Enviar email">
                                                        <i class="bi bi-envelope-fill text-info me-1"></i>
                                                        <?= htmlspecialchars($c->getEmail()); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin email</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?page=clientes&action=editar&id=<?= $c->getId(); ?>" 
                                                       class="btn btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Editar cliente">
                                                        <i class="bi bi-pencil"></i>
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
                            No se encontraron clientes que coincidan con tu búsqueda.
                            <button id="limpiarFiltro2" class="btn btn-outline-warning btn-sm ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar búsqueda
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay clientes registrados. 
                            <a href="index.php?page=clientes&action=crear" class="alert-link">¡Registra el primero!</a>
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
    const limpiarFiltro = document.getElementById('limpiarFiltro');
    const limpiarFiltro2 = document.getElementById('limpiarFiltro2');
    const table = document.getElementById('clientesTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const sinResultados = document.getElementById('sinResultados');
    const contadorResultados = document.getElementById('contadorResultados');
    
    if (!tbody) return;
    
    const filas = Array.from(tbody.querySelectorAll('tr'));
    const totalClientes = filas.length;
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        const textoFiltro = searchInput.value.toLowerCase().trim();
        let filasVisibles = 0;
        
        filas.forEach(fila => {
            const nombre = fila.dataset.nombre || '';
            const telefono = fila.dataset.telefono || '';
            const email = fila.dataset.email || '';
            
            const coincide = textoFiltro === '' || 
                nombre.includes(textoFiltro) || 
                telefono.includes(textoFiltro) ||
                email.includes(textoFiltro);
            
            if (coincide) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Actualizar contador
        contadorResultados.textContent = filasVisibles;
        
        // Mostrar/ocultar mensaje de sin resultados
        if (filasVisibles === 0 && textoFiltro !== '') {
            sinResultados.classList.remove('d-none');
        } else {
            sinResultados.classList.add('d-none');
        }
        
        // Aplicar estilo al campo con filtro activo
        if (textoFiltro !== '') {
            searchInput.classList.add('filtro-activo');
        } else {
            searchInput.classList.remove('filtro-activo');
        }
    }
    
    // Función para limpiar filtros
    function limpiarFiltros() {
        searchInput.value = '';
        searchInput.classList.remove('filtro-activo');
        aplicarFiltros();
        searchInput.focus();
    }
    
    // Event listeners
    let timeoutId;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(aplicarFiltros, 300);
    });
    
    limpiarFiltro.addEventListener('click', limpiarFiltros);
    if (limpiarFiltro2) {
        limpiarFiltro2.addEventListener('click', limpiarFiltros);
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
            limpiarFiltros();
        }
    });
    
    // Placeholder dinámico
    const placeholders = [
        'Buscar por nombre...',
        'Buscar por teléfono...',
        'Buscar por email...',
        'Buscar por nombre, teléfono o email...'
    ];
    
    let placeholderIndex = 0;
    setInterval(() => {
        if (document.activeElement !== searchInput) {
            searchInput.placeholder = placeholders[placeholderIndex];
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
        }
    }, 3000);
    
    searchInput.addEventListener('focus', function() {
        this.placeholder = 'Buscar por nombre, teléfono o email... (Ctrl+F)';
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.placeholder = 'Buscar por nombre, teléfono o email...';
        }
    });
    
    console.log('✅ Sistema de filtros de clientes inicializado');
    console.log(`👥 Total clientes: ${totalClientes}`);
});
</script>

</body>
</html>