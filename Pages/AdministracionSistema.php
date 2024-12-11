<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['num_control']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
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
  <title>Administración del Sistema - ITS Jerez</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://d3js.org/d3.v7.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .main-content {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <!-- Barra de navegación (Navbar) -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Administración del Sistema</a>
  
    <!-- Botón para pantallas pequeñas -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <!-- Enlaces de la barra de navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="../Scripts/backend/logout.php">Salir</a>
        </li>
    </div>
  </nav>
<!-- Contenido Principal -->
<div class="main-content">
  <div class="container">
  <p>Gestiona usuarios, reportes y monitoriza el uso de la plataforma.</p>

  <!-- Pestañas de navegación -->
  <ul class="nav nav-tabs" id="adminTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab">Gestión de Usuarios</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="permisos-tab" data-toggle="tab" href="#permisos" role="tab">Reportes</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="monitoreo-tab" data-toggle="tab" href="#monitoreo" role="tab">Monitorización del Sistema</a>
    </li>
  </ul>

  <div class="tab-content">
    <!-- Gestión de Usuarios -->
    <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
      <div class="card my-4">
        <div class="card-body">
          <h5 class="card-title">Lista de Usuarios</h5>
          <div class="form-inline mb-3">
            <input type="text" class="form-control mr-2" id="buscarUsuario" placeholder="Buscar usuario..." oninput="buscarUsuario()">
          </div>
          <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalUsuario">Agregar Nuevo Usuario</button>

          <!-- Tabla de usuarios -->
          <table class="table table-hover">
            <thead>
              <tr>
                <th>num_control</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="listaUsuarios">
              <!-- Aquí se cargarán dinámicamente los usuarios -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

   <!-- Reportes -->
  <div class="tab-pane fade" id="permisos" role="tabpanel">
    <div class="card my-4">
      <div class="card-body">
        <div class="container">
          <h1>Reportes y Estadísticas</h1>
    <p>Visualiza las estadísticas de uso de la plataforma, el rendimiento de los tutores, y la satisfacción de los estudiantes.</p>
    
    <!-- Filtros para seleccionar el período de tiempo -->
    <form id="formFiltros" method="POST">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="mesSeleccionado">Seleccionar Mes</label>
          <select class="form-control" id="mesSeleccionado" name="mesSeleccionado">
            <option value="01">Enero</option>
            <option value="02">Febrero</option>
            <option value="03">Marzo</option>
            <option value="01">Abril</option>
            <option value="02">Mayo</option>
            <option value="03">junio</option>
            <option value="01">Julio</option>
            <option value="02">Agosto</option>
            <option value="03">Septiembre</option>
            <option value="01">Octubre</option>
            <option value="02">Noviemre</option>
            <option value="03">Diciembre</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="anoSeleccionado">Seleccionar Año</label>
          <select class="form-control" id="anoSeleccionado" name="anoSeleccionado">
            <option value="2024">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
    </form>
    
    <!-- Gráfico de Tutorías por mes -->
    <div class="card mt-4">
      <div class="card-body">
        <h5 class="card-title">Tutorías Realizadas por Mes</h5>
        <canvas id="graficoTutoriasMes" width="400" height="200"></canvas>
      </div>
    </div>
    
    <!-- Tabla de rendimiento de tutores -->
    <div class="card mt-4">
      <div class="card-body">
        <h5 class="card-title">Rendimiento de los Tutores</h5>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Tutor</th>
              <th>Tutorías Realizadas</th>
              <th>Calificación Promedio</th>
            </tr>
          </thead>
          <tbody id="listaRendimientoTutores">
            <!-- Aquí se cargarán dinámicamente los tutores -->
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Gráfico de Satisfacción de los Estudiantes -->
    <div class="card mt-4">
      <div class="card-body">
        <h5 class="card-title">Satisfacción Promedio de los Estudiantes</h5>
        <svg id="graficoSatisfaccion" width="400" height="200"></svg>
      </div>
    </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Monitorización del Sistema -->
    <div class="tab-pane fade" id="monitoreo" role="tabpanel">
      <div class="card my-4">
        <div class="card-body">
          <h5 class="card-title">Estado del Sistema</h5>
          <div class="row">
            <div class="col-md-4">
              <div class="alert alert-info">
                <strong>Usuarios Totales:</strong> <span id="usuariosTotales">0</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="alert alert-info">
                <strong>Tutorías Programadas:</strong> <span id="tutoriasTotales">0</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="alert alert-info">
                <strong>Usuarios Activos Hoy:</strong> <span id="usuariosActivos">0</span>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="alert alert-primary">
                <strong>Tutores:</strong> <span id="tutores">0</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="alert alert-primary">
                <strong>Estudiantes:</strong> <span id="estudiantes">0</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="alert alert-primary">
                <strong>Administradores:</strong> <span id="administradores">0</span>
              </div>
            </div>
          </div>
          
          <!-- Gráfico de uso de la plataforma -->
          <canvas id="graficoMonitoreo" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
  </div> 
</div>

<!-- Modal para agregar usuarios -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsuarioLabel">Agregar usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUsuario">
          <div class="form-group">
            <label for="numControl">Número de Control</label>
            <input type="number" class="form-control" id="numControl" name="num_control" required>
          </div>
          <div class="form-group">
            <label for="nombreUsuario">Nombre</label>
            <input type="text" class="form-control" id="nombreUsuario"  name="nombre" required>
          </div>
          <div class="form-group">
            <label for="primerApellidoUsuario">Primer Apellido</label>
            <input type="text" class="form-control" id="primerApellidoUsuario" name="primer_apellido" required>
          </div>
          <div class="form-group">
            <label for="segundoApellidoUsuario">Segundo Apellido</label>
            <input type="text" class="form-control" id="segundoApellidoUsuario" name="segundo_apellido">
          </div>
          <div class="form-group">
            <label for="emailUsuario">Email</label>
            <input type="email" class="form-control" id="emailUsuario"  name="correo_electronico" required>
          </div>
          <div class="form-group">
            <label for="contraseñaUsuario">Contraseña</label>
            <input type="password" class="form-control" id="contraseñaUsuario" name="contraseña" required>
          </div>
          <div class="form-group">
            <label for="confirmarContraseñaUsuario">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="confirmarContraseñaUsuario" name="confirmar_contraseña" required>
          </div>
          <div class="form-group">
            <label for="rolUsuario">Rol</label>
            <select class="form-control" id="rolUsuario" name="tipo_usuario" required onchange="toggleSemestre()">
              <option value="Administrador">Administrador</option>
              <option value="Tutor">Tutor</option>
              <option value="Estudiante">Estudiante</option>
            </select>
          </div>
          <div class="form-group">
            <label for="semestreUsuario">Semestre</label>
            <input type="number" class="form-control" id="semestreUsuario" name="semestre" min="1" max="12">
          </div>
          <div class="form-group">
            <label for="fechaNacUsuario">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fechaNacUsuario" name="fecha_nac" required>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Usuario</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar usuarios -->
<div class="modal fade"id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarUsuario">
          <!-- Número de Control (readonly) -->
          <div class="form-group">
            <label for="editarNumControl">Número de Control</label>
            <input type="number" class="form-control" id="editarNumControl" name="num_control" readonly>
          </div>
          <!-- Nombre -->
          <div class="form-group">
            <label for="editarNombreUsuario">Nombre</label>
            <input type="text" class="form-control" id="editarNombreUsuario" name="nombre" required>
          </div>
          <!-- Primer Apellido -->
          <div class="form-group">
            <label for="editarPrimerApellidoUsuario">Primer Apellido</label>
            <input type="text" class="form-control" id="editarPrimerApellidoUsuario" name="primer_apellido" required>
          </div>
          <!-- Segundo Apellido -->
          <div class="form-group">
            <label for="editarSegundoApellidoUsuario">Segundo Apellido</label>
            <input type="text" class="form-control" id="editarSegundoApellidoUsuario" name="segundo_apellido">
          </div>
          <!-- Correo Electrónico -->
          <div class="form-group">
            <label for="editarEmailUsuario">Correo Electrónico</label>
            <input type="email" class="form-control" id="editarEmailUsuario" name="correo_electronico" required>
          </div>
          <!-- Tipo de Usuario -->
          <div class="form-group">
            <label for="editarRolUsuario">Rol</label>
            <select class="form-control" id="editarRolUsuario" name="tipo_usuario" required onchange="toggleSemestreEditar()">
              <option value="Administrador">Administrador</option>
              <option value="Tutor">Tutor</option>
              <option value="Estudiante">Estudiante</option>
            </select>
          </div>
          <!-- Semestre  -->
          <div class="form-group">
            <label for="editarSemestreUsuario">Semestre</label>
            <input type="number" class="form-control" id="editarSemestreUsuario" name="semestre" min="1" max="12">
          </div>
          <!-- Fecha de Nacimiento -->
          <div class="form-group">
            <label for="editarFechaNacUsuario">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="editarFechaNacUsuario" name="fecha_nac" required>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

  function cargarUsuarios() {
  fetch('../Scripts/backend/listar_usuarios.php')
    .then(response => response.json())
    .then(usuarios => {
      const listaUsuarios = document.getElementById('listaUsuarios');
      listaUsuarios.innerHTML = ''; // Limpiar la tabla antes de llenarla

      usuarios.forEach(usuario => {
        const fila = `
          <tr>
            <td>${usuario.num_control}</td>
            <td>${usuario.nombre} ${usuario.primer_apellido} ${usuario.segundo_apellido || ''}</td>
            <td>${usuario.correo_electronico}</td>
            <td>${usuario.tipo_usuario}</td>
            <td>
              <button class="btn btn-warning btn-sm" onclick="abrirEditorUsuario(${usuario.num_control})">Editar</button>
              <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${usuario.num_control})">Eliminar</button>
            </td>
          </tr>
        `;
        listaUsuarios.insertAdjacentHTML('beforeend', fila);
      });
    })
    .catch(error => console.error('Error al cargar usuarios:', error));
}

// Llama a cargarUsuarios cuando la página se carga
document.addEventListener("DOMContentLoaded", cargarUsuarios);

document.getElementById('formUsuario').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('../Scripts/backend/agregar_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);  // Esto muestra el mensaje completo en la consola
        alert(result);  // Muestra el mensaje específico que PHP devuelve

        // Solo cerrar el modal y recargar usuarios si la inserción fue exitosa
        if (result.includes("Registro exitoso")) {
            $('#modalUsuario').modal('hide');
            cargarUsuarios();  // Función que recarga la lista de usuarios
        }
    })
    .catch(error => console.error('Error al guardar usuario:', error));
});


function eliminarUsuario(num_control) {
  if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
    fetch('../Scripts/backend/eliminar_usuario.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `num_control=${num_control}`
    })
    .then(response => response.text())
    .then(result => {
      console.log(result);
      alert("Usuario eliminado con éxito.");
      cargarUsuarios();
    })
    .catch(error => console.error('Error al eliminar usuario:', error));
  }
}

function toggleSemestre() {
  const rol = document.getElementById('rolUsuario').value;
  const semestreField = document.getElementById('semestreUsuario');
  
  if (rol === 'Tutor' || rol === 'Administrador') {
    semestreField.value = '';  // Vacía el campo de semestre
    semestreField.disabled = true;  // Deshabilita el campo
  } else {
    semestreField.disabled = false;  // Habilita el campo para Estudiante
  }
}

function toggleSemestreEditar() {
  const rol = document.getElementById('editarRolUsuario').value;
  const semestreField = document.getElementById('editarSemestreUsuario');
  semestreField.disabled = false; 
  if (rol === 'Tutor' || rol === 'Administrador') {
    semestreField.value = '';  
    semestreField.disabled = true; 
  } else if (rol === 'Estudiante') {
    semestreField.disabled = false;  
  }
}

// Llama a toggleSemestre al cargar el modal para establecer el estado inicial
document.addEventListener("DOMContentLoaded", () => {
  toggleSemestre();
});

document.addEventListener("DOMContentLoaded", () => {
  toggleSemestreEditar();
});
function abrirEditorUsuario(num_control) {
  // Solicitar los datos del usuario al backend
  fetch(`../Scripts/backend/obtener_usuario.php?num_control=${num_control}`)
    .then(response => response.json())
    .then(usuario => {
      // Rellenar el formulario de edición con los datos obtenidos
      document.getElementById('editarNumControl').value = usuario.num_control;
      document.getElementById('editarNombreUsuario').value = usuario.nombre;
      document.getElementById('editarPrimerApellidoUsuario').value = usuario.primer_apellido;
      document.getElementById('editarSegundoApellidoUsuario').value = usuario.segundo_apellido;
      document.getElementById('editarEmailUsuario').value = usuario.correo_electronico;
      document.getElementById('editarRolUsuario').value = usuario.tipo_usuario;
      document.getElementById('editarSemestreUsuario').value = usuario.semestre;
      document.getElementById('editarFechaNacUsuario').value = usuario.fecha_nac;

      // Mostrar el modal
      $('#modalEditarUsuario').modal('show');
    })
    .catch(error => console.error('Error al cargar datos del usuario:', error));
}


document.getElementById('formEditarUsuario').addEventListener('submit', function(event) {
  event.preventDefault();

  const formData = new FormData(this);

  fetch('../Scripts/backend/editar_usuario.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    alert(result); // Muestra el mensaje del backend

    // Cierra el modal y recarga la lista de usuarios
    $('#modalEditarUsuario').modal('hide');
    cargarUsuarios();
  })
  .catch(error => console.error('Error al actualizar usuario:', error));
});

function buscarUsuario() {
  const textoBusqueda = document.getElementById('buscarUsuario').value.toLowerCase();
  const filas = document.querySelectorAll('#listaUsuarios tr');

  filas.forEach(fila => {
    const nombre = fila.cells[0].textContent.toLowerCase();
    const correo = fila.cells[1].textContent.toLowerCase();
    const rol = fila.cells[2].textContent.toLowerCase();

    // Verificar si el texto de búsqueda está en alguna de las columnas
    if (nombre.includes(textoBusqueda) || correo.includes(textoBusqueda) || rol.includes(textoBusqueda)) {
      fila.style.display = '';  // Mostrar la fila
    } else {
      fila.style.display = 'none';  // Ocultar la fila
    }
  });
}
function cargarMonitorizacion() {
  fetch('../Scripts/backend/monitorizacion_sistema.php')
    .then(response => response.json())
    .then(data => {
      // Calcular los porcentajes de cada tipo de usuario
      const porcentajeTutores = ((data.tutores / data.usuariosTotales) * 100).toFixed(2);
      const porcentajeEstudiantes = ((data.estudiantes / data.usuariosTotales) * 100).toFixed(2);
      const porcentajeAdministradores = ((data.administradores / data.usuariosTotales) * 100).toFixed(2);

      // Actualizar la interfaz con los datos obtenidos
      document.getElementById('usuariosTotales').textContent = data.usuariosTotales;
      document.getElementById('tutores').textContent = `${data.tutores} (${porcentajeTutores}%)`;
      document.getElementById('estudiantes').textContent = `${data.estudiantes} (${porcentajeEstudiantes}%)`;
      document.getElementById('administradores').textContent = `${data.administradores} (${porcentajeAdministradores}%)`;
      document.getElementById('tutoriasTotales').textContent = data.tutoriasTotales;
      document.getElementById('usuariosActivos').textContent = data.usuariosActivosSemana;

      // Llamar a la función para actualizar el gráfico
      actualizarGraficoUsuarios(data);
    })
    .catch(error => console.error('Error al cargar datos de monitorización:', error));
}

// Llama a cargarMonitorizacion al cargar la página
document.addEventListener("DOMContentLoaded", cargarMonitorizacion);

let graficoUsuarios; // Variable global para almacenar el gráfico de usuarios

function actualizarGraficoUsuarios(data) {
  const ctx = document.getElementById('graficoMonitoreo').getContext('2d');

  if (graficoUsuarios) {
    // Si el gráfico ya existe, actualiza los datos
    graficoUsuarios.data.datasets[0].data = [data.tutores, data.estudiantes, data.administradores];
    graficoUsuarios.update();
  } else {
    // Crear el gráfico por primera vez
    graficoUsuarios = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Tutores', 'Estudiantes', 'Administradores'],
        datasets: [{
          data: [data.tutores, data.estudiantes, data.administradores],
          backgroundColor: ['#007bff', '#28a745', '#ffc107']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          }
        }
      }
    });
  }
}

</script>
<script>
  // 1. Interceptar el envío del formulario para evitar la recarga de la página
  document.getElementById('formFiltros').addEventListener('submit', function(event) {
    event.preventDefault();  // Evitar el envío tradicional del formulario

    const mesSeleccionado = document.getElementById('mesSeleccionado').value;
    const anoSeleccionado = document.getElementById('anoSeleccionado').value;

    // Enviar la solicitud de datos con los filtros seleccionados
    fetch('estadisticas.php', {
      method: 'POST',
      body: new URLSearchParams({
        mesSeleccionado: mesSeleccionado,
        anoSeleccionado: anoSeleccionado
      })
    })
    .then(response => response.json())
    .then(data => {
      // Crear gráfico con Chart.js
      const ctx = document.getElementById('graficoTutoriasMes').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Tutorías'],
          datasets: [{
            label: 'Total Tutorías',
            data: [data.length > 0 ? data[0].total_tutorias : 0],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    });

    // Obtener el rendimiento de los tutores
    fetch('rendimiento_tutores.php')
      .then(response => response.json())
      .then(data => {
        const tablaRendimiento = document.getElementById('listaRendimientoTutores');
        tablaRendimiento.innerHTML = '';  // Limpiar la tabla antes de agregar nuevos datos
        data.forEach(tutor => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${tutor.nombre} ${tutor.primer_apellido}</td>
            <td>${tutor.tutorias_realizadas}</td>
            <td>${tutor.calificacion_promedio.toFixed(2)}</td>
          `;
          tablaRendimiento.appendChild(row);
        });
      });

    // Obtener la satisfacción promedio de los estudiantes
    fetch('satisfaccion_estudiantes.php')
      .then(response => response.json())
      .then(data => {
        const satisfaction = data.satisfaccion_promedio || 0;
        const svg = d3.select('#graficoSatisfaccion');
        const width = svg.attr('width');
        const height = svg.attr('height');

        const radius = Math.min(width, height) / 2;
        const arc = d3.arc().outerRadius(radius - 10).innerRadius(0);
        const pie = d3.pie().sort(null).value(d => d);

        const dataSatisfaccion = pie([satisfaction, 100 - satisfaction]);

        svg.append('g')
           .attr('transform', 'translate(' + width / 2 + ',' + height / 2 + ')')
           .selectAll('.arc')
           .data(dataSatisfaccion)
           .enter().append('path')
           .attr('class', 'arc')
           .attr('d', arc)
           .style('fill', (d, i) => i === 0 ? 'green' : 'red');
      });
  });

  // Cargar los gráficos al iniciar la página
  document.addEventListener('DOMContentLoaded', function() {
    const mesSeleccionado = document.getElementById('mesSeleccionado').value;
    const anoSeleccionado = document.getElementById('anoSeleccionado').value;

    // Llamada inicial para cargar los gráficos
    fetch('estadisticas.php', {
      method: 'POST',
      body: new URLSearchParams({
        mesSeleccionado: mesSeleccionado,
        anoSeleccionado: anoSeleccionado
      })
    })
    .then(response => response.json())
    .then(data => {
      // Crear gráfico con Chart.js
      const ctx = document.getElementById('graficoTutoriasMes').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Tutorías'],
          datasets: [{
            label: 'Total Tutorías',
            data: [data.length > 0 ? data[0].total_tutorias : 0],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    });
  });
</script>
</body>
</html>