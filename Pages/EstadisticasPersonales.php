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
  <title>Estadísticas Personales - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {
      margin-top: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
    .main-content {margin-top: 30px;  padding: 20px;}
  </style>
</head>
<body>
  <div id="navbarContainer"></div>
  <div class="main-content">
<div class="container">
  <h1 class="mt-4">Estadísticas Personales</h1>
  <p>Revisa un resumen de tu rendimiento en la plataforma de tutorías.</p>

  <!-- Resumen estadístico -->
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Tutorías Impartidas</h5>
          <p class="card-text" id="cantidadTutorias">0</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Tiempo Total (hrs)</h5>
          <p class="card-text" id="tiempoTotal">0</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <h5 class="card-title">Nivel de Satisfacción</h5>
          <p class="card-text" id="nivelSatisfaccion">0</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Gráficos de rendimiento -->
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Tutorías por Mes</h5>
          <canvas id="graficoTutoriasPorMes"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center">Nivel de Satisfacción</h5>
          <canvas id="graficoSatisfaccion"></canvas>
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

<!-- Script para mostrar estadísticas y gráficos -->
<script>
  // Ejemplo de datos
  const estadisticas = {
    cantidadTutorias: 34,
    tiempoTotal: 56.5,
    nivelSatisfaccion: 4.5,
    tutoriasPorMes: [4, 6, 8, 5, 7, 9, 10, 8, 6, 4, 5, 8],  // Ejemplo de sesiones por mes
    satisfaccionEstudiantes: [30, 10, 5, 3, 1]  // Ejemplo de calificaciones (5 estrellas, 4, 3, etc.)
  };

  // Mostrar estadísticas en el resumen
  document.getElementById('cantidadTutorias').textContent = estadisticas.cantidadTutorias;
  document.getElementById('tiempoTotal').textContent = estadisticas.tiempoTotal;
  document.getElementById('nivelSatisfaccion').textContent = estadisticas.nivelSatisfaccion.toFixed(1);

  // Gráfico de Tutorías por Mes
  const ctxTutorias = document.getElementById('graficoTutoriasPorMes').getContext('2d');
  new Chart(ctxTutorias, {
    type: 'line',
    data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      datasets: [{
        label: 'Tutorías por Mes',
        data: estadisticas.tutoriasPorMes,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Gráfico de Nivel de Satisfacción
  const ctxSatisfaccion = document.getElementById('graficoSatisfaccion').getContext('2d');
  new Chart(ctxSatisfaccion, {
    type: 'doughnut',
    data: {
      labels: ['5 Estrellas', '4 Estrellas', '3 Estrellas', '2 Estrellas', '1 Estrella'],
      datasets: [{
        label: 'Satisfacción de Estudiantes',
        data: estadisticas.satisfaccionEstudiantes,
        backgroundColor: [
          'rgba(75, 192, 192, 0.6)',
          'rgba(54, 162, 235, 0.6)',
          'rgba(255, 206, 86, 0.6)',
          'rgba(255, 99, 132, 0.6)',
          'rgba(153, 102, 255, 0.6)'
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(255, 99, 132, 1)',
          'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
</script>
<script>
  fetch('navbartutor.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navbarContainer').innerHTML = data;
    });
</script>
</body>
</html>
