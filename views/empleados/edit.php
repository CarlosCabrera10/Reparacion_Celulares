<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Editar Empleado</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?page=empleados&action=editar&id=<?= $empleado->getId() ?>">
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                        value="<?= htmlspecialchars($empleado->getNombre()) ?>" required>
                </div>

                <!-- Usuario -->
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" 
                        value="<?= htmlspecialchars($empleado->getUsuario()) ?>" required>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña (dejar vacío para no cambiarla):</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <!-- Cargo -->
                <div class="mb-3">
                    <label for="cargo" class="form-label">Cargo:</label>
                    <select class="form-select" id="cargo" name="cargo" required>
                        <option value="Tecnico" <?= $empleado->getCargo()=="Tecnico" ? "selected" : "" ?>>Técnico</option>
                        <option value="Recepcionista" <?= $empleado->getCargo()=="Recepcionista" ? "selected" : "" ?>>Recepcionista</option>
                        <option value="Administrador" <?= $empleado->getCargo()=="Administrador" ? "selected" : "" ?>>Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="index.php?page=empleados&action=index" class="btn btn-secondary ms-2">⬅️ Volver</a>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
