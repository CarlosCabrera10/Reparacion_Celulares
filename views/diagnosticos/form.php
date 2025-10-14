<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <?= isset($diagnostico) ? "Editar Diagnóstico" : "Nuevo Diagnóstico" ?>
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- Celular -->
                <div class="mb-3">
                    <label for="id_celular" class="form-label">Celular</label>
                    <select class="form-select" id="id_celular" name="id_celular" required>
                        <option value="">Seleccione un celular</option>
                        <?php
                        $preselectCelularId = $_GET['celular_id'] ?? null;
                        foreach ($celulares as $c): ?>
                            <option value="<?= $c->getId() ?>"
                                <?= (isset($diagnostico) && $diagnostico->getIdCelular() == $c->getId()) || ($preselectCelularId && $preselectCelularId == $c->getId()) ? "selected" : "" ?>>
                                <?= htmlspecialchars($c->getMarca() . " - " . $c->getModelo()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Empleado (solo técnicos) -->
                <div class="mb-3">
                    <label for="id_empleado" class="form-label">Empleado (Técnico)</label>
                    <select class="form-select" id="id_empleado" name="id_empleado" required>
                        <option value="">Seleccione un técnico</option>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e->getId() ?>"
                                <?= isset($diagnostico) && $diagnostico->getIdEmpleado() == $e->getId() ? "selected" : "" ?>>
                                <?= htmlspecialchars($e->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                    <label for="fecha_diagnostico" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha_diagnostico" name="fecha_diagnostico"
                        value="<?= isset($diagnostico) ? $diagnostico->getFechaDiagnostico() : date('Y-m-d') ?>"
                        min="2023-01-01" max="<?= date('Y-m-d') ?>" required
                        title="Ingrese una fecha válida">
                </div>


                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required maxlength="500"
                        placeholder="Describe el diagnóstico (máx. 500 caracteres)"><?= isset($diagnostico) ? htmlspecialchars($diagnostico->getDescripcion()) : '' ?></textarea>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <?= isset($diagnostico) ? "Actualizar" : "Guardar" ?>
                    </button>
                    <a href="index.php?page=diagnosticos&action=index" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inputmask para IMEI y otras máscaras -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap (si los tienes en otros elementos)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Aplicar máscara a IMEI: solo 15 dígitos
    Inputmask("999999999999999").mask("#imei");

    // Validación adicional en submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const descripcion = document.getElementById('descripcion').value.trim();
        if (descripcion.length === 0 || descripcion.length > 500) {
            alert("La descripción es inválida. Debe tener entre 1 y 500 caracteres.");
            e.preventDefault();
            return false;
        }
        const imei = document.getElementById('imei').value.trim();
        if (imei !== "" && !/^\d{15}$/.test(imei)) {
            alert("El IMEI debe tener exactamente 15 dígitos.");
            e.preventDefault();
            return false;
        }
    });
});
</script>
