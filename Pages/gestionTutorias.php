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
  <title>Gestión de Tutorías estudiantes - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .navbar-brand {
      font-size: 1.5em;
      font-weight: bold;
    }
    .main-content {
      margin-top: 80px; 
      padding: 20px;
    }
    .card-custom {
      transition: transform 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-custom:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }
    .table thead th {
      background-color: #343a40;
      color: white;
    }
    .card {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <div id="navbarContainer"></div>
 
<!-- Contenido Principal -->
<div class="main-content">
  <div class="container">
    <!-- Formulario para Reservar una Tutoría -->
    <div class="card card-custom mb-4">
      <div class="card-body">
        <h5 class="card-title">Reservar una Tutoría</h5>
        <form id="formReservarTutoria" onsubmit="reservarTutoria(event)">
          <div class="form-row">
              <div class="form-group col-md-4">
                  <label for="fechaTutoria">Fecha</label>
                  <input type="date" class="form-control" id="fechaTutoria" name="fecha" required>
              </div>
              <div class="form-group col-md-4">
                  <label for="horaTutoria">Hora</label>
                  <input type="time" class="form-control" id="horaTutoria" name="hora" required>
              </div>
              <div class="form-group col-md-4">
                <label for="tutorAsignado">Tutor Asignado</label>
                <select class="form-control" id="tutorAsignado" name="id_tutor" required>
                 
                </select>
              </div>              
          </div>
          <div class="form-group">
              <label for="temaTutoria">Tema de la Tutoría</label>
              <input type="text" class="form-control" id="temaTutoria" name="titulo" placeholder="Escribe el tema o asunto a tratar" required>
          </div>
          <div class="form-group">
              <label for="descripcionTutoria">Descripción Adicional (opcional)</label>
              <textarea class="form-control" id="descripcionTutoria" name="descripcion" rows="3" placeholder="Detalles adicionales sobre la tutoría"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Reservar Tutoría</button>
      </form>
      
      </div>
    </div>

    <!-- Consulta de Tutorías con búsqueda -->
    <div class="card mb-4" id="listaTutorias">
      <div class="card-body">
        <h5 class="card-title">Lista de Tutorías</h5>
        
      <div class="form-inline mb-3">
        <input class="form-control mr-2" id="buscarTutoria" placeholder="Buscar tutoría..." oninput="buscarTutorias()">
      </div>


        <!-- Tabla de Sesiones Programadas -->
        <h3>Mis Sesiones Programadas</h3>
        <table class="table table-striped table-hover mt-3">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Tema</th>
              <th>Tutor</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaSesiones">
            
          </tbody>
         
        </table>
      </div>
    </div>

    <!-- Historial de modificaciones -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Historial de Modificaciones</h5>
        <div id="historialModificaciones">
          
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
function cargarTutorias() {
  fetch('../Scripts/backend/listar_tutorias.php')
    .then(response => response.json())
    .then(tutorias => {
      const tabla = document.getElementById('tablaSesiones');
      tabla.innerHTML = ''; 

      tutorias.forEach(tutoria => {
        const fila = `
          <tr>
            <td>${tutoria.fecha}</td>
            <td>${tutoria.hora}</td>
            <td>${tutoria.titulo}</td>
            <td>${tutoria.id_tutor}</td>
            <td>${tutoria.estado}</td>
            <td>
              <button class="btn btn-success btn-sm" onclick="confirmarSesion(${tutoria.id_tutoria})">Completada</button>
              <button class="btn btn-danger btn-sm" onclick="cancelarSesion(${tutoria.id_tutoria})">Cancelar</button>
              <button class="btn btn-warning btn-sm" onclick="editarTutoria(${tutoria.id_tutoria})">Editar</button>
              <button class="btn btn-danger btn-sm" onclick="eliminarTutoria(${tutoria.id_tutoria})">Eliminar</button>
            </td>
          </tr>
        `;
        tabla.insertAdjacentHTML('beforeend', fila);
      });
       
        buscarTutorias();
    })
    .catch(error => {
      console.error("Error al cargar tutorías:", error);
      alert("Hubo un problema al cargar las tutorías. Revisa la consola para más detalles.");
    });
}

document.addEventListener("DOMContentLoaded", cargarTutorias);
function reservarTutoria(event) {
  event.preventDefault();

  const formData = new FormData(document.getElementById('formReservarTutoria'));

  fetch('../Scripts/backend/reservar_tutoria.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    console.log("Resultado de la reserva:", result);

    if (result.includes("éxito")) {
      alert("Tutoría reservada con éxito");
      cargarTutorias();  // Cargar nuevamente la tabla
      document.getElementById('formReservarTutoria').reset();
    } else {
      alert("Error: " + result);
    }
  })
  .catch(error => {
    console.error("Error al reservar la tutoría:", error);
    alert("Hubo un error al reservar la tutoría. Por favor, intenta nuevamente.");
  });
}

function buscarTutorias() {
  const input = document.getElementById('buscarTutoria').value.toLowerCase();
  const filas = document.getElementById('tablaSesiones').getElementsByTagName('tr');

  for (let i = 0; i < filas.length; i++) {
    const celdas = filas[i].getElementsByTagName('td');
    let encontrado = false;

    for (let j = 0; j < celdas.length; j++) {
      if (celdas[j] && celdas[j].innerText.toLowerCase().includes(input)) {
        encontrado = true; 
        break;
      }
    }

    filas[i].style.display = encontrado ? '' : 'none';
  }
}
function confirmarSesion(idTutoria) {
  fetch('../Scripts/backend/confirmar_tutoria.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id_tutoria=${idTutoria}`
  })
  .then(response => response.text())
  .then(result => {
    console.log(result);
    alert("Tutoría confirmada.");
    cargarTutorias(); 
    cargarHistorial(idTutoria);
  })
  .catch(error => {
    console.error("Error al confirmar la tutoría:", error);
  });
}

function cancelarSesion(idTutoria) {
  fetch('../Scripts/backend/cancelar_tutoria.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id_tutoria=${idTutoria}`
  })
  .then(response => response.text())
  .then(result => {
    console.log(result);
    alert("Tutoría cancelada.");
    cargarTutorias(); 
    cargarHistorial(idTutoria);
  })
  .catch(error => {
    console.error("Error al cancelar la tutoría:", error);
  });
}

function editarTutoria(idTutoria) {
  fetch(`../Scripts/backend/obtener_tutoria.php?id_tutoria=${idTutoria}`)
    .then(response => response.json())
    .then(tutoria => {
      document.getElementById('editarIdTutoria').value = tutoria.id_tutoria;
      document.getElementById('editarTitulo').value = tutoria.titulo;
      document.getElementById('editarDescripcion').value = tutoria.descripcion;
      document.getElementById('editarFecha').value = tutoria.fecha;
      document.getElementById('editarHora').value = tutoria.hora;
      cargarHistorial(idTutoria);
      $('#editarModal').modal('show');
    })
    .catch(error => console.error('Error al obtener los datos de la tutoría:', error));
}

function eliminarTutoria(idTutoria) {
  if (confirm("¿Estás seguro de que deseas eliminar esta tutoría?")) {
    fetch('../Scripts/backend/eliminar_tutoria.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_tutoria=${idTutoria}`
    })
    .then(response => response.text())
    .then(result => {
      console.log(result);
      alert("Tutoría eliminada.");
      cargarTutorias(); 
    })
    .catch(error => {
      console.error("Error al eliminar la tutoría:", error);
    });
  }
}
function guardarCambiosTutoria() {
  const formData = new FormData(document.getElementById('formEditarTutoria'));

  fetch('../Scripts/backend/editar_tutoria.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    console.log(result);
    alert("Tutoría actualizada exitosamente.");
    
    $('#editarModal').modal('hide');
    
    cargarTutorias();
  })
  .catch(error => console.error('Error al guardar los cambios de la tutoría:', error));
}
function cargarHistorial(idTutoria) {
  fetch(`../Scripts/backend/listar_historial.php?id_tutoria=${idTutoria}`)
    .then(response => response.json())
    .then(historial => {
      const historialContainer = document.getElementById('historialModificaciones');
      historialContainer.innerHTML = '';

      if (historial.length === 0) {
        historialContainer.innerHTML = '<p>No hay modificaciones registradas para esta tutoría.</p>';
        return;
      }

      historial.forEach(item => {
        const accionTexto = `${item.accion.charAt(0).toUpperCase() + item.accion.slice(1)}`;
        const fechaTexto = new Date(item.fecha_modificacion).toLocaleString();
        const usuarioId = item.usuario_id;

        const historialItem = document.createElement('p');
        historialItem.innerText = `${fechaTexto} - Acción: ${accionTexto}, Usuario ID: ${usuarioId}`;
        historialContainer.appendChild(historialItem);
      });
    })
    .catch(error => {
      console.error("Error al cargar el historial de modificaciones:", error);
    });
}

function cargarTutores() {
  fetch('../Scripts/backend/obtener_tutores.php')
    .then(response => response.json())
    .then(tutores => {
      const selectTutor = document.getElementById('tutorAsignado');
      selectTutor.innerHTML = ''; 

      tutores.forEach(tutor => {
        const option = document.createElement('option');
        option.value = tutor.num_control; 
        option.textContent = `${tutor.nombre} ${tutor.primer_apellido} ${tutor.segundo_apellido || ''}`;
        selectTutor.appendChild(option);
      });
    })
    .catch(error => {
      console.error("Error al cargar tutores:", error);
    });
}

document.addEventListener("DOMContentLoaded", cargarTutores);

</script>
<script>
  fetch('navbarest.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navbarContainer').innerHTML = data;
    });
</script>

<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarModalLabel">Editar Tutoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulario para editar la tutoría -->
        <form id="formEditarTutoria">
          <input type="hidden" id="editarIdTutoria" name="id_tutoria">
          <div class="form-group">
            <label for="editarTitulo">Tema de la Tutoría</label>
            <input type="text" class="form-control" id="editarTitulo" name="titulo" required>
          </div>
          <div class="form-group">
            <label for="editarDescripcion">Descripción</label>
            <textarea class="form-control" id="editarDescripcion" name="descripcion" rows="3"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editarFecha">Fecha</label>
              <input type="date" class="form-control" id="editarFecha" name="fecha" required>
            </div>
            <div class="form-group col-md-6">
              <label for="editarHora">Hora</label>
              <input type="time" class="form-control" id="editarHora" name="hora" required>
            </div>
          </div>
          <button type="button" class="btn btn-primary" onclick="guardarCambiosTutoria()">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
