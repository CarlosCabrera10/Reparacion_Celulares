<?php
// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  .navbar-custom {
      background-color: #1f363d;
  }
  .navbar-custom .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #fff;
  }
  .navbar-custom .nav-link {
      color: #cfd8dc;
      transition: color 0.3s;
  }
  .navbar-custom .nav-link:hover {
      color: #ffffff;
  }
  .navbar-custom .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
  }
</style>

<?php
// Depuración: mostrar el valor de cargo
if (isset($_SESSION['cargo'])) {
    echo '<!-- Cargo actual: ' . htmlspecialchars($_SESSION['cargo']) . ' -->';
} else {
    echo '<!-- No hay cargo en la sesión -->';
}
?>

<?php if (isset($_SESSION['usuario_cargo'])): ?>
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Taller Celulares</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <?php if (isset($_SESSION["usuario_cargo"])): ?>
          <?php if ($_SESSION["usuario_cargo"] === "Administrador"): ?>

            <li class="nav-item">
              <a class="nav-link" href="index.php?page=empleados&action=index">Empleados</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=reparacion&action=index">Reparaciones</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=reportes&action=index">Reportes</a>
            </li>

          <?php elseif ($_SESSION["usuario_cargo"] === "Recepcionista"): ?>
            
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=clientes&action=index">Clientes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=celulares&action=index">Celulares</a>
            </li>
              <li class="nav-item">
              <a class="nav-link" href="index.php?page=diagnosticos&action=index">Diagnosticos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=reparacion&action=index">Reparaciones</a>
            </li>

          <?php elseif ($_SESSION["usuario_cargo"] === "Tecnico"): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php?page=reparaciones&action=index">Reparaciones</a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="index.php?page=auth&action=logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
