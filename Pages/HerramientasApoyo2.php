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
  <title>Herramientas de Apoyo para Tutorías - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .card {
      margin-bottom: 20px;
    }
    .tool-button {
      margin: 5px 0;
    }
    .main-content {margin-top: 30px;  padding: 20px;}
  </style>
</head>
<body>
  <div id="navbarContainer"></div>
  <div class="main-content">
<div class="container">
  <h1 class="mt-4">Herramientas de Apoyo para Tutorías</h1>
  <p>Accede rápidamente a tus herramientas de apoyo favoritas para mejorar la experiencia de las tutorías.</p>

  <!-- Accesos rápidos a herramientas -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Accesos Rápidos a Herramientas</h5>
      <button class="btn btn-primary tool-button" onclick="abrirGoogleDrive()">Google Drive</button>
      <button class="btn btn-info tool-button" onclick="abrirOneDrive()">OneDrive</button>
      <button class="btn btn-success tool-button" onclick="abrirZoom()">Zoom</button>
      <!-- Agregar más herramientas si es necesario -->
    </div>
  </div>

  <!-- Conectar cuentas -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Conectar Cuentas</h5>
      <p>Conecta tus cuentas para un acceso rápido y seguro a tus archivos y reuniones.</p>
      <button class="btn btn-outline-primary tool-button" onclick="conectarGoogle()">Conectar Google</button>
      <button class="btn btn-outline-info tool-button" onclick="conectarMicrosoft()">Conectar Microsoft</button>
      <button class="btn btn-outline-success tool-button" onclick="conectarZoom()">Conectar Zoom</button>
    </div>
  </div>
  <!-- Integración con WhatsApp -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Integración con WhatsApp</h5>
      <p>Permite configurar un enlace directo para comunicarte con estudiantes y tutores a través de WhatsApp.</p>
      <form id="formWhatsApp">
        <div class="form-group">
          <label for="numeroWhatsApp">Número de WhatsApp</label>
          <input type="tel" class="form-control" id="numeroWhatsApp" placeholder="+521234567890" required>
        </div>
        <div class="form-group">
          <label for="mensajeInicial">Mensaje Inicial</label>
          <input type="text" class="form-control" id="mensajeInicial" placeholder="Escribe tu mensaje de bienvenida" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Enlace</button>
      </form>
      <p id="estadoWhatsApp" class="mt-3">Estado: No configurado</p>
      <p id="enlaceWhatsApp" class="integracion-activada d-none">Enlace generado: <a href="#" target="_blank" id="linkWhatsApp">Abrir en WhatsApp</a></p>
    </div>
  </div>
</div>
  <!-- Historial de accesos recientes -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Historial de Accesos Recientes</h5>
      <ul class="list-group" id="historialAccesos">
        <li class="list-group-item">No hay accesos recientes.</li>
      </ul>
    </div>
  </div>
</div>
</div>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para gestionar herramientas y accesos -->
<script>
  const historialAccesos = [];

  // Funciones para abrir herramientas
  function abrirGoogleDrive() {
    window.open("https://drive.google.com", "_blank");
    registrarAcceso("Google Drive");
  }

  function abrirOneDrive() {
    window.open("https://onedrive.live.com", "_blank");
    registrarAcceso("OneDrive");
  }

  function abrirZoom() {
    window.open("https://zoom.us", "_blank");
    registrarAcceso("Zoom");
  }

  // Funciones para conectar cuentas (simulación)
  function conectarGoogle() {
    alert("Conectando con Google...");
  }

  function conectarMicrosoft() {
    alert("Conectando con Microsoft...");
  }

  function conectarZoom() {
    alert("Conectando con Zoom...");
  }

  // Registrar y mostrar historial de accesos
  function registrarAcceso(herramienta) {
    const fecha = new Date().toLocaleString();
    historialAccesos.unshift({ herramienta, fecha }); // Agregar al inicio del historial
    mostrarHistorial();
  }

  function mostrarHistorial() {
    const listaHistorial = document.getElementById('historialAccesos');
    listaHistorial.innerHTML = '';

    if (historialAccesos.length === 0) {
      listaHistorial.innerHTML = '<li class="list-group-item">No hay accesos recientes.</li>';
    } else {
      historialAccesos.slice(0, 5).forEach(acceso => { // Mostrar solo los últimos 5 accesos
        const item = document.createElement('li');
        item.classList.add('list-group-item');
        item.textContent = `${acceso.herramienta} - ${acceso.fecha}`;
        listaHistorial.appendChild(item);
      });
    }
  }

  // Cargar historial inicial
  mostrarHistorial();

  // Función para configurar el enlace de WhatsApp
  document.getElementById('formWhatsApp').addEventListener('submit', function(e) {
    e.preventDefault();

    const numero = document.getElementById('numeroWhatsApp').value;
    const mensaje = encodeURIComponent(document.getElementById('mensajeInicial').value);
    const enlace = `https://wa.me/${numero}?text=${mensaje}`;

    const estadoWhatsApp = document.getElementById('estadoWhatsApp');
    const enlaceWhatsApp = document.getElementById('enlaceWhatsApp');
    const linkWhatsApp = document.getElementById('linkWhatsApp');

    linkWhatsApp.href = enlace;
    enlaceWhatsApp.classList.remove('d-none');
    estadoWhatsApp.textContent = 'Estado: Configurado';
    registrarAcceso("whats")
  });
  fetch('navbartutor.html')
      .then(response => response.text())
      .then(data => {
        document.getElementById('navbarContainer').innerHTML = data;
      });
</script>

</body>
</html>