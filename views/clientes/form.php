<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <?= isset($cliente) ? "Editar Cliente" : "Nuevo Cliente" ?>
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= isset($cliente) ? htmlspecialchars($cliente->getNombre()) : '' ?>" required>
                </div>

                <!-- Teléfono con guion automático -->
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" 
                           value="<?= isset($cliente) ? htmlspecialchars($cliente->getTelefono()) : '' ?>" 
                           required maxlength="9"
                           placeholder="7777-7777">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= isset($cliente) ? htmlspecialchars($cliente->getEmail()) : '' ?>">
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <?= isset($cliente) ? "Actualizar" : "Guardar" ?>
                    </button>
                    <a href="index.php?page=clientes&action=index" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefonoInput = document.getElementById('telefono');

    telefonoInput.addEventListener('input', function(e) {
        // Eliminar todo lo que no sea número
        let numeros = this.value.replace(/\D/g, '');

        // Insertar guion después del cuarto número
        if (numeros.length > 4) {
            numeros = numeros.slice(0, 4) + '-' + numeros.slice(4, 8);
        }

        this.value = numeros;
    });
});
</script>
