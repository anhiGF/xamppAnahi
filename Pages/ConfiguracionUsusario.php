<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['num_control']) ) {
    // Redirige al login si no ha iniciado sesión o no es administrador
    header("Location: ../Index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración del Usuario - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {margin-top: 30px;  padding: 20px}
    .card {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div id="navbarContainer"></div>
  <div class="main-content">
<div class="container">
  <h1 class="mt-4">Configuración del Usuario</h1>
  <p>Administra tu información personal, cambia tu contraseña y ajusta tus preferencias de notificación.</p>

  <!-- Información personal -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Información Personal</h5>
      <form id="formInformacionPersonal">
        <div class="form-group">
          <label for="nombreUsuario">Nombre</label>
          <input type="text" class="form-control" id="nombreUsuario" placeholder="Escribe tu nombre" required>
        </div>
        <div class="form-group">
          <label for="emailUsuario">Correo Electrónico</label>
          <input type="email" class="form-control" id="emailUsuario" placeholder="Escribe tu correo" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Información</button>
      </form>
    </div>
  </div>

  <!-- Cambio de contraseña -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Cambio de Contraseña</h5>
      <form id="formCambioContraseña">
        <div class="form-group">
          <label for="contraseñaActual">Contraseña Actual</label>
          <input type="password" class="form-control" id="contraseñaActual" required>
        </div>
        <div class="form-group">
          <label for="nuevaContraseña">Nueva Contraseña</label>
          <input type="password" class="form-control" id="nuevaContraseña" required>
        </div>
        <div class="form-group">
          <label for="confirmarNuevaContraseña">Confirmar Nueva Contraseña</label>
          <input type="password" class="form-control" id="confirmarNuevaContraseña" required>
        </div>
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
      </form>
    </div>
  </div>

  <!-- Preferencias de notificación -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Preferencias de Notificación</h5>
      <form id="formNotificaciones">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="notificacionesCorreo">
          <label class="form-check-label" for="notificacionesCorreo">Recibir notificaciones por correo electrónico</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="notificacionesSMS">
          <label class="form-check-label" for="notificacionesSMS">Recibir notificaciones por SMS</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="notificacionesPush">
          <label class="form-check-label" for="notificacionesPush">Recibir notificaciones push</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar Preferencias</button>
      </form>
    </div>
  </div>
</div>
</div>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  fetch('navbartutor.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navbarContainer').innerHTML = data;
    });
</script>
<!-- Script para manejar las configuraciones del usuario -->
<script>
 
</script>

</body>
</html>