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
  <title>Panel del Tutor - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {font-family: Arial, sans-serif; }
    .navbar-brand {font-size: 1.5em; font-weight: bold; }
    .main-content {margin-top: 80px;  padding: 20px;}
   .card-custom { transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    .card-custom:hover {  transform: translateY(-5px); box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2); }
  </style>
</head>
<body>
  <div id="navbarContainer"></div>
  <!-- Contenido Principal -->
  <div class="main-content">
    <div class="container">
      <h1>Bienvenido</h1>

      <!-- Tarjetas de acceso rápido a las funcionalidades -->
      <div class="row">
         <!-- Gestión de Perfil -->
         <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Gestión de Perfil</h5>
              <p class="card-text">Actualiza tu información personal y profesional.</p>
              <a href="./ConfiguracionUsusario.php" class="btn btn-primary">Editar Perfil</a>
            </div>
          </div>
        </div>
        
        <!-- Sesiones de Tutoría -->
        <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Sesiones de Tutoría</h5>
              <p class="card-text">Accede a tus próximas sesiones y gestiona las sesiones pasadas.</p>
              <a href="./gestionTutoriastutores.php" class="btn btn-primary">Ver Sesiones</a>
            </div>
          </div>
        </div>

        <!-- Gestión de Horarios -->
        <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Gestión de Horarios</h5>
              <p class="card-text">Configura tus horarios de tutoría disponibles.</p>
              <a href="./GestionHorariosTutores.php" class="btn btn-primary">Gestionar Horarios</a>
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        <!-- Estudiantes Asignados -->
        <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Estadisticas personales</h5>
              <p class="card-text">Revisa tus Estadisticas.</p>
              <a href="./EstadisticasPersonales.php" class="btn btn-primary">Ver Estadisticas</a>
            </div>
          </div>
        </div>

        <!-- Integración con apps web -->
        <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Apps webs</h5>
              <p class="card-text">Accede y comparte materiales con tus estudiantes a través de apps.</p>
              <a href="./HerramientasApoyo2.php" class="btn btn-primary">abrir apps </a>
            </div>
          </div>
        </div>
         <!-- Material -->
         <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Material de apoyo</h5>
              <p class="card-text">Maneja el contenido para tus tutorias y estudiantes.</p>
              <a href="./GestionContenidoTutorias.php" class="btn btn-primary">Materiales</a>
            </div>
          </div>
        </div>
      </div>
         <!-- Comunicacion -->
         <div class="col-md-4">
          <div class="card card-custom mb-4">
            <div class="card-body">
              <h5 class="card-title">Comunicacion</h5>
              <p class="card-text">Comunicate con tus estudiantes.</p>
              <a href="./ComunicacionDirecta.php" class="btn btn-primary">Mensajes</a>
            </div>
          </div>
        </div>
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
</body>
</html>