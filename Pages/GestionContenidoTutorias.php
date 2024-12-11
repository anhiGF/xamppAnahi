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
  <title>Gestión de Contenido de Tutorías - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {
      margin-top: 30px; 
      padding: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
    .action-buttons button {
      margin-right: 5px;
    }
  </style>
</head>
<body>
<div class="container">
  <div id="navbarContainer"></div>
  <div class="main-content">
  <h1 class="mt-4">Organización y Gestión del Contenido de Tutorías</h1>
  <p>Sube, organiza y gestiona los documentos y materiales que utilizarás en tus sesiones de tutoría.</p>
  <!-- Formulario para agregar contenido -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Agregar Nuevo Contenido</h5>
      <form id="formContenido">
        <div class="form-group">
          <label for="tituloContenido">Título</label>
          <input type="text" class="form-control" id="tituloContenido" placeholder="Título del contenido" required>
        </div>
        <div class="form-group">
          <label for="descripcionContenido">Descripción</label>
          <textarea class="form-control" id="descripcionContenido" rows="3" placeholder="Descripción del contenido"></textarea>
        </div>
        <div class="form-group">
          <label for="categoriaContenido">Categoría</label>
          <select class="form-control" id="categoriaContenido" required>
            <option value="documentos">Documentos</option>
            <option value="guías">Guías</option>
            <option value="videos">Videos</option>
            <option value="asignaciones">Asignaciones</option>
            <!-- Otras categorías según sea necesario -->
          </select>
        </div>
        <div class="form-group">
          <label for="archivoContenido">Seleccionar Archivo</label>
          <input type="file" class="form-control-file" id="archivoContenido" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir Contenido</button>
      </form>
    </div>
  </div>

  <!-- Lista de contenidos -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Contenido Disponible</h5>
      <input type="text" id="busqueda" class="form-control mb-3" placeholder="Buscar contenido...">
      <ul class="list-group" id="listaContenidos">
        <li class="list-group-item">No hay contenidos disponibles.</li>
      </ul>
    </div>
  </div>
</div>
</div>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para gestionar contenido -->
<script>
  fetch('navbartutor.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navbarContainer').innerHTML = data;
    });
</script>

</body>
</html>