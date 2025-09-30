<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  /* Estilo personalizado del navbar */
  .navbar-custom {
      background-color: #1f363d; /* color personalizado */
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

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?page=empleados&action=index">Taller Celulares</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=empleados&action=index">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=empleados&action=index">Empleados</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=reparaciones&action=index">Reparaciones</a>
        </li>
        <li class="nav-item">
      <a class="nav-link" href="index.php?page=auth&action=logout">Cerrar sesión</a>
      </li>
    </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
