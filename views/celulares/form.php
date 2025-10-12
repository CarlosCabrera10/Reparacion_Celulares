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
                <!-- Cliente -->
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

                <!-- Marca -->
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getMarca()) : '' ?>" 
                           required maxlength="50" 
                           pattern="[A-Za-z0-9\s]+" 
                           title="Solo se permiten letras, números y espacios. Máximo 50 caracteres">
                </div>

                <!-- Modelo -->
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getModelo()) : '' ?>" 
                           required maxlength="50" 
                           pattern="[A-Za-z0-9\s]+" 
                           title="Solo se permiten letras, números y espacios. Máximo 50 caracteres">
                </div>

                <!-- IMEI -->
                <div class="mb-3">
                    <label for="imei" class="form-label">IMEI</label>
                    <input type="text" class="form-control" id="imei" name="imei" 
                           value="<?= isset($celular) ? htmlspecialchars($celular->getImei()) : '' ?>" 
                           maxlength="15" 
                           pattern="\d{15}" 
                           placeholder="123456789012345"
                           title="El IMEI debe contener 15 números">
                </div>

                <!-- Botones -->
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inputmask para IMEI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscara al IMEI
    Inputmask("999999999999999").mask("#imei");

    // Validación extra en submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const marca = document.getElementById('marca').value.trim();
        const modelo = document.getElementById('modelo').value.trim();
        const imei = document.getElementById('imei').value.trim();

        if (!/^[A-Za-z0-9\s]{1,50}$/.test(marca)) {
            alert("Marca inválida. Solo letras, números y espacios (máx. 50 caracteres).");
            e.preventDefault();
            return false;
        }

        if (!/^[A-Za-z0-9\s]{1,50}$/.test(modelo)) {
            alert("Modelo inválido. Solo letras, números y espacios (máx. 50 caracteres).");
            e.preventDefault();
            return false;
        }

        if (imei !== "" && !/^\d{15}$/.test(imei)) {
            alert("IMEI inválido. Debe contener exactamente 15 números.");
            e.preventDefault();
            return false;
        }
    });
});
</script>
