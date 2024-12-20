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
  <title>Gestión de Horarios de Tutorías - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {margin-top: 80px;  padding: 20px;}
    .card {
      margin-bottom: 20px;
    }
    .table-horarios td, .table-horarios th {
      text-align: center;
      vertical-align: middle;
    }
    .action-buttons button {
      margin: 0 2px;
    }
  </style>
</head>
<body>
  <div id="navbarContainer"></div>
  <div class="main-content">
<div class="container">
  <h1 class="mt-4">Gestión de Horarios de Tutorías</h1>
  <p>Organiza y gestiona tus horarios de tutorías según tu disponibilidad.</p>

  <!-- Formulario para agregar/editar horario -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Agregar Bloque de Disponibilidad</h5>
      <form id="formHorario">
        <div class="form-group">
          <label for="diaSemana">Día de la Semana</label>
          <select class="form-control" id="diaSemana" required>
            <option value="lunes">Lunes</option>
            <option value="martes">Martes</option>
            <option value="miércoles">Miércoles</option>
            <option value="jueves">Jueves</option>
            <option value="viernes">Viernes</option>
            <option value="sábado">Sábado</option>
          </select>
        </div>
        <div class="form-group">
          <label for="horaInicio">Hora de Inicio</label>
          <input type="time" class="form-control" id="horaInicio" required>
        </div>
        <div class="form-group">
          <label for="horaFin">Hora de Fin</label>
          <input type="time" class="form-control" id="horaFin" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Horario</button>
      </form>
    </div>
  </div>

  <!-- Tabla de horarios semanales -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Horarios Semanales</h5>
      <table class="table table-bordered table-horarios">
        <thead>
          <tr>
            <th>Hora</th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sábado</th>
            <th>Domingo</th>
          </tr>
        </thead>
        <tbody id="tablaHorarios">
          <!-- Los horarios se mostrarán aquí -->
        </tbody>
      </table>
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
<!-- Script para gestionar los horarios -->

</body>
</html>