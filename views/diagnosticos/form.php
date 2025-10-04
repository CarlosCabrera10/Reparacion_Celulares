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
                        <?php foreach ($celulares as $c): ?>
                            <option value="<?= $c->getId() ?>"
                                <?= isset($diagnostico) && $diagnostico->getIdCelular() == $c->getId() ? "selected" : "" ?>>
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
                        value="<?= isset($diagnostico) ? $diagnostico->getFechaDiagnostico() : date('Y-m-d') ?>" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= isset($diagnostico) ? htmlspecialchars($diagnostico->getDescripcion()) : '' ?></textarea>
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
