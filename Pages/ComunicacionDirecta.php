<?php
session_start();
include("../Scripts/backend/conexion.php");

if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    header("Location: ../Index.html");
    exit();
}

// Obtener el id_remitente desde la sesión
$id_remitente = $_SESSION['num_control']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comunicación Directa - ITS Jerez</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
    .chat-container { display: flex; height: 75vh; margin-top: 10px; }
    .contact-list { width: 25%; border-right: 1px solid #ddd; overflow-y: auto; }
    .contact-item { padding: 10px; cursor: pointer; transition: background-color 0.2s; }
    .contact-item:hover, .contact-item.active { background-color: #e9ecef; }
    .chat-window { width: 80%; display: flex; flex-direction: column; padding: 10px; }
    .messages { flex-grow: 1; overflow-y: auto; padding: 5px; border-bottom: 1px solid #ddd; }
    .message-input { display: flex; align-items: center; padding: 10px; }
    .message-input input { flex-grow: 1; margin-right: 10px; }
    .message { padding: 8px; margin-bottom: 5px; border-radius: 10px; max-width: 95%; }
    .message.sent { background-color: #d1e7dd; align-self: flex-end; }
    .message.received { background-color: #e2e3e5; align-self: flex-start; }
    .main-content {margin-top: 30px;  padding: 15px; }
  </style>
</head>
<body>
<div id="navbarContainer"></div>
<div class="main-content">
<div id="navbarContainer"></div>
  <div class="container">
    <h1 class="mt-4">Mensajes</h1>
    <div class="chat-container">
      <div class="contact-list" id="contactList"></div>
      <div class="chat-window">
        <div class="messages" id="ventanaMensajes"></div>
        <div class="message-input">
          <input type="text" id="inputMensaje" placeholder="Escribe un mensaje..." />
          <button class="btn btn-primary" onclick="enviarMensaje()">Enviar</button>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    // Definir variables globales
    let idDestinatario = null; // Se actualizará cuando se seleccione un contacto
    let idRemitente = <?php echo json_encode($id_remitente); ?>; // Inyectar el id_remitente en el script

    document.addEventListener("DOMContentLoaded", function() {
      cargarContactos();
    });

    function cargarContactos() {
      fetch('../Scripts/backend/obtener_contactos.php')
        .then(response => response.json())
        .then(contactos => {
          const contactList = document.getElementById('contactList');
          contactList.innerHTML = '';

          if (contactos.error) {
            contactList.innerHTML = `<p>${contactos.error}</p>`;
            return;
          }

          contactos.forEach(contacto => {
            const contactItem = document.createElement('div');
            contactItem.className = 'contact-item';
            contactItem.textContent = `${contacto.nombre} ${contacto.primer_apellido}`;
            contactItem.onclick = () => {
              // Guardar id_destinatario en la variable global
              idDestinatario = contacto.num_control;
              console.log("ID del destinatario seleccionado:", idDestinatario);
              cargarConversacion();
            };
            contactList.appendChild(contactItem);
          });
        })
        .catch(error => console.error('Error al cargar contactos:', error));
    }

    function cargarConversacion() {
      if (!idDestinatario) {
        console.error("No se ha seleccionado un destinatario.");
        return;
      }

      console.log("ID Remitente:", idRemitente); // Usar la variable idRemitente correctamente

      fetch(`../Scripts/backend/cargar_mensajes.php?id_destinatario=${idDestinatario}`)
        .then(response => response.json())
        .then(mensajes => {
          console.log("Mensajes recibidos:", mensajes); // Verificar mensajes recibidos
          const ventanaMensajes = document.getElementById('ventanaMensajes');
          ventanaMensajes.innerHTML = '';

          if (mensajes.message) {
            ventanaMensajes.textContent = mensajes.message;
            return;
          }

          mensajes.forEach(mensaje => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${mensaje.id_remitente == idRemitente ? 'sent' : 'received'}`;
            messageDiv.textContent = mensaje.contenido;
            ventanaMensajes.appendChild(messageDiv);
          });
        })
        .catch(error => console.error('Error al cargar mensajes:', error));
    }

    function enviarMensaje() {
      const contenido = document.getElementById('inputMensaje').value;
      if (contenido.trim() === '' || !idDestinatario) {
        console.error("Contenido vacío o destinatario no seleccionado.");
        return;
      }

      const formData = new FormData();
      formData.append('contenido', contenido);
      formData.append('id_destinatario', idDestinatario);

      fetch('../Scripts/backend/enviar_mensaje.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          document.getElementById('inputMensaje').value = '';
          cargarConversacion(); // Recarga la conversación después de enviar
        } else {
          console.error(result.error || "Error desconocido al enviar el mensaje.");
          alert(result.error || "No se pudo enviar el mensaje.");
        }
      })
      .catch(error => console.error('Error al enviar mensaje:', error));
    }
    fetch('navbartutor.html')
      .then(response => response.text())
      .then(data => {
        document.getElementById('navbarContainer').innerHTML = data;
      });
  </script>
</body>
</html>
