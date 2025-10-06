<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Reparación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalles de la Reparación</h1>

        <div class="card">
            <div class="card-header">
                <h5>Información de la Reparación</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Reparación:</strong> <?php echo htmlspecialchars($reparacion->getId()); ?></p>
                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($reparacion->getEstado()); ?></p>
                        <p><strong>Fecha de Ingreso:</strong> <?php echo htmlspecialchars($reparacion->getFechaIngreso()); ?></p>
                        <p><strong>Fecha de Entrega:</strong> <?php echo htmlspecialchars($reparacion->getFechaEntrega()); ?></p>
                        <p><strong>Costo:</strong> $<?php echo number_format($reparacion->getCosto(), 2); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($cliente->getNombre()); ?></p>
                        <p><strong>Celular:</strong> <?php echo htmlspecialchars($celular->getMarca() . ' ' . $celular->getModelo()); ?></p>
                        <p><strong>IMEI:</strong> <?php echo htmlspecialchars($celular->getImei()); ?></p>
                        <p><strong>Técnico:</strong> <?php echo htmlspecialchars($empleado->getNombre()); ?></p>
                        <p><strong>Fecha de Diagnóstico:</strong> <?php echo htmlspecialchars($diagnostico->getFechaDiagnostico()); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Descripción del Diagnóstico Inicial:</strong></p>
                        <p><?php echo htmlspecialchars($diagnostico->getDescripcion()); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Diagnóstico de la Reparación:</strong></p>
                        <p><?php echo htmlspecialchars($reparacion->getDiagnostico() ?? 'No especificado'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
