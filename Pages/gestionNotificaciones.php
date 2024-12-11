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
  <title>Gestión de Notificaciones - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {
      margin-top: 20px;
    }
    .notification-read {
      background-color: #f1f1f1;
    }
    /* Estilo para las notificaciones emergentes (toasts) */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1050;
    }
  </style>
</head>
<body>

<!-- Barra de navegación con badge de notificaciones no leídas -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">ITS Jerez</a>
  <div class="ml-auto">
    <a href="#" class="btn btn-primary">
      Notificaciones <span class="badge badge-light" id="badgeNotificaciones">0</span>
    </a>
  </div>
</nav>

<div class="container">
  <h1 class="mt-4">Gestión de Notificaciones</h1>
  <p>Revisa las notificaciones sobre tus tutorías, cancelaciones o cambios en el horario.</p>

  <!-- Tabla de notificaciones -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Tus Notificaciones</h5>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Mensaje</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="listaNotificaciones">
          <!-- Aquí se cargarán dinámicamente las notificaciones -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Contenedor para los toasts (notificaciones emergentes) -->
<div class="toast-container" id="toastContainer">
  <!-- Los toasts se generarán aquí -->
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para manejar las notificaciones y los toasts -->
<script>
  // Simulación de datos de notificaciones
  let notificaciones = [
    { id: 1, tipo: 'Nueva Tutoría', mensaje: 'Se ha agendado una nueva tutoría con el estudiante Juan Pérez.', fecha: '2024-10-16', leido: false },
    { id: 2, tipo: 'Cancelación de Tutoría', mensaje: 'La tutoría del 2024-10-17 con Laura Martínez ha sido cancelada.', fecha: '2024-10-15', leido: false },
    { id: 3, tipo: 'Cambio de Horario', mensaje: 'La tutoría del 2024-10-18 ha cambiado al 2024-10-19 a las 10:00 AM.', fecha: '2024-10-14', leido: true }
  ];

  // Función para mostrar las notificaciones en la tabla
  function mostrarNotificaciones() {
    const listaNotificaciones = document.getElementById('listaNotificaciones');
    listaNotificaciones.innerHTML = ''; // Limpiar las notificaciones anteriores

    notificaciones.forEach(notificacion => {
      const fila = document.createElement('tr');
      if (notificacion.leido) fila.classList.add('notification-read'); // Marcar como leída

      fila.innerHTML = `
        <td>${notificacion.tipo}</td>
        <td>${notificacion.mensaje}</td>
        <td>${notificacion.fecha}</td>
        <td>
          <button class="btn btn-success btn-sm" onclick="marcarComoLeida(${notificacion.id})">Marcar como leída</button>
          <button class="btn btn-danger btn-sm" onclick="eliminarNotificacion(${notificacion.id})">Eliminar</button>
        </td>
      `;
      listaNotificaciones.appendChild(fila);
    });

    // Actualizar el badge de notificaciones no leídas
    actualizarBadgeNotificaciones();
  }

  // Función para marcar una notificación como leída
  function marcarComoLeida(id) {
    const notificacion = notificaciones.find(n => n.id === id);
    if (notificacion) {
      notificacion.leido = true;
      mostrarNotificaciones();
    }
  }

  // Función para eliminar una notificación
  function eliminarNotificacion(id) {
    notificaciones = notificaciones.filter(n => n.id !== id);
    mostrarNotificaciones();
  }

  // Función para actualizar el badge de notificaciones no leídas
  function actualizarBadgeNotificaciones() {
    const noLeidas = notificaciones.filter(n => !n.leido).length;
    document.getElementById('badgeNotificaciones').textContent = noLeidas;
  }

  // Función para mostrar un toast (notificación emergente)
  function mostrarToast(tipo, mensaje) {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.classList.add('toast', 'show');
    toast.style.minWidth = '300px';
    toast.innerHTML = `
      <div class="toast-header">
        <strong class="mr-auto">${tipo}</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
      </div>
      <div class="toast-body">
        ${mensaje}
      </div>
    `;
    toastContainer.appendChild(toast);

    // Remover el toast después de 3 segundos
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }

  // Simulación: agregar una nueva notificación después de 5 segundos
  setTimeout(() => {
    const nuevaNotificacion = {
      id: 4,
      tipo: 'Nueva Tutoría',
      mensaje: 'Se ha agendado una tutoría con María López.',
      fecha: '2024-10-17',
      leido: false
    };
    notificaciones.push(nuevaNotificacion);
    mostrarNotificaciones();
    mostrarToast(nuevaNotificacion.tipo, nuevaNotificacion.mensaje);
  }, 5000);

  // Mostrar las notificaciones cuando cargue la página
  mostrarNotificaciones();
</script>

</body>
</html>
