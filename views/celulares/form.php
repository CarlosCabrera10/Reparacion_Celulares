<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <?= isset($celular) ? "Editar Celular" : "Nuevo Celular" ?>
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">Cliente</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cl): ?>
                            <option value="<?= $cl->getId() ?>"
                                <?= isset($celular) && $celular->getIdCliente() == $cl->getId() ? "selected" : "" ?>>
                                <?= htmlspecialchars($cl->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getMarca()) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getModelo()) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="imei" class="form-label">IMEI</label>
                    <input type="text" class="form-control" id="imei" name="imei" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getImei()) : '' ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <?= isset($celular) ? "Actualizar" : "Guardar" ?>
                    </button>
                    <a href="index.php?page=celulares&action=index" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
