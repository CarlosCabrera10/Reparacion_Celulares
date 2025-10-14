<?php include __DIR__ . "/../layout/menu.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <?= isset($celular) ? "Editar Celular" : "Nuevo Celular" ?>
            </h4>
        </div>
        <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <form method="POST" action="">
                <!-- Cliente: autocomplete en lugar de combobox -->
                <div class="mb-3 position-relative">
                    <label for="cliente_search" class="form-label">Cliente</label>

                    <div class="input-group">
                        <input type="text" class="form-control" id="cliente_search" placeholder="Buscar cliente por correo (email)" aria-label="Buscar cliente" autocomplete="off" required>
                        <input type="hidden" id="id_cliente" name="id_cliente" value="<?= isset($celular) ? htmlspecialchars($celular->getIdCliente()) : ($_GET['cliente_id'] ?? '') ?>">
                        <a href="index.php?page=clientes&action=crear" class="btn btn-outline-secondary">Nuevo</a>
                    </div>

                    <div id="cliente_suggestions" class="list-group position-absolute w-100" style="z-index:1050; max-height:200px; overflow:auto; display:none;"></div>
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
                           title="El IMEI debe contener 15 números" required>
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

        if (imei === "") {
            alert("El IMEI es obligatorio y debe contener exactamente 15 números.");
            e.preventDefault();
            return false;
        }

        if (!/^\d{15}$/.test(imei)) {
            alert("IMEI inválido. Debe contener exactamente 15 números.");
            e.preventDefault();
            return false;
        }
    });
});
</script>

<script>
// Autocomplete para cliente
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('cliente_search');
    const hiddenId = document.getElementById('id_cliente');
    const suggestions = document.getElementById('cliente_suggestions');
    let timer = null;

    // Cargar cache de clientes embebida en la página (evita peticiones AJAX adicionales)
    const CLIENTS_CACHE = (function(){
        try {
            return <?= json_encode(array_map(function($cl){ return ['id'=>$cl->getId(),'nombre'=>$cl->getNombre(),'telefono'=>$cl->getTelefono(),'email'=>$cl->getEmail()]; }, $clientes)); ?>;
        } catch(e) { return []; }
    })();

    // Si se pasó cliente_id por GET, intentar mostrar el nombre usando la cache
    const preId = hiddenId.value;
    if (preId) {
        const found = CLIENTS_CACHE.find(c => String(c.id) === String(preId));
        if (found) searchInput.value = (found.email || found.nombre) + ' — ' + found.nombre;
    } else if (typeof window !== 'undefined' && <?= isset($celular) ? 'true' : 'false' ?>) {
        // Si estamos en modo edición y por alguna razón hiddenId no se llenó, usar el cliente del objeto $celular
        const editClientId = <?= isset($celular) ? (int)$celular->getIdCliente() : 'null' ?>;
        if (editClientId) {
            const found2 = CLIENTS_CACHE.find(c => String(c.id) === String(editClientId));
            if (found2) {
                hiddenId.value = found2.id;
                searchInput.value = (found2.email || found2.nombre) + ' — ' + found2.nombre;
            }
        }
    }

    function clearSuggestions() {
        suggestions.innerHTML = '';
        suggestions.style.display = 'none';
    }

    function showSuggestions(items) {
        suggestions.innerHTML = '';
        if (!items || items.length === 0) { clearSuggestions(); return; }
            items.forEach(it => {
            const el = document.createElement('button');
            el.type = 'button';
            el.className = 'list-group-item list-group-item-action';
            // Mostrar correo y nombre
            el.textContent = `${it.email ?? it.nombre} — ${it.nombre}`;
            el.dataset.id = it.id;
            el.addEventListener('click', function() {
                hiddenId.value = this.dataset.id;
                searchInput.value = this.textContent;
                clearSuggestions();
            });
            suggestions.appendChild(el);
        });
        suggestions.style.display = 'block';
    }

    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        hiddenId.value = '';
        if (timer) clearTimeout(timer);
        if (q.length < 2) { clearSuggestions(); return; }
        timer = setTimeout(() => {
            // Filtrar localmente la cache por email (o nombre si no hay email)
            const qLow = q.toLowerCase();
            const matches = CLIENTS_CACHE.filter(c => (c.email && c.email.toLowerCase().includes(qLow)) || (c.nombre && c.nombre.toLowerCase().includes(qLow)) ).slice(0,10);
            if (matches.length === 0) {
                suggestions.innerHTML = '<div class="list-group-item">No se encontraron clientes</div>';
                suggestions.style.display = 'block';
            } else {
                showSuggestions(matches);
            }
        }, 200);
    });

    // Manejo de teclado: arriba/abajo/enter
    let focusedIndex = -1;
    searchInput.addEventListener('keydown', function(e) {
        const items = suggestions.querySelectorAll('.list-group-item');
        if (!items.length) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusedIndex = (focusedIndex + 1) % items.length;
            items.forEach((it, i) => it.classList.toggle('active', i === focusedIndex));
            items[focusedIndex].scrollIntoView({block: 'nearest'});
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusedIndex = (focusedIndex - 1 + items.length) % items.length;
            items.forEach((it, i) => it.classList.toggle('active', i === focusedIndex));
            items[focusedIndex].scrollIntoView({block: 'nearest'});
        } else if (e.key === 'Enter') {
            if (focusedIndex >= 0 && items[focusedIndex]) {
                e.preventDefault();
                items[focusedIndex].click();
            } else {
                // If user pressed enter without selecting, try to search exact
                const q = this.value.trim();
                if (q.length > 0) {
                    const endpoint = "<?= URL ?>index.php?page=clientes&action=search&q=" + encodeURIComponent(q) + "&limit=5";
                    fetch(endpoint)
                        .then(r => r.json())
                        .then(data => {
                            if (data.length === 1) {
                                // auto-select single match
                                hiddenId.value = data[0].id;
                                searchInput.value = (data[0].email || data[0].nombre) + ' — ' + data[0].nombre;
                                clearSuggestions();
                            } else {
                                // keep suggestions open if multiple
                                showSuggestions(data);
                            }
                        }).catch(()=>{});
                }
            }
        }
    });

    // Cerrar al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!suggestions.contains(e.target) && e.target !== searchInput) clearSuggestions();
    });
});
</script>
