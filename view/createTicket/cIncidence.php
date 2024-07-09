<?php
  require_once("../../config/connection.php");
  if(isset($_SESSION["us_Id"])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once("../MainHead/head.php");?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php require_once("../mainHeader/header.php");?>
  <?php require_once("../mainNav/nav.php");?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Nuevo Ticket</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../home/index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Incidencia</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Registro</h3>
        </div>
        <form id="ticketForm" enctype="multipart/form-data" action="../../controller/proxyIncidence.php" method="POST">
          <div class="card-body">
            <div class="form-group">
              <label for="campaignName">Nombre de Campaña</label>
              <input type="text" class="form-control" id="campaignName" name="campaignName" placeholder="Empresa" required>
            </div>
            <div class="form-group">
              <label for="channel">Canal Afectado</label>
              <select class="select2 form-control" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="channel" id="channel">
                  <option value="Facebook">Facebook</option>
                  <option value="Voz">Voz</option>
                  <option value="WhatsApp">WhatsApp</option>
                  <option value="Email">Email</option>
                  <option value="Videollamada">Videollamada</option>
                  <option value="Instagram">Instagram</option>
              </select>
            </div>
            <div class="form-group">
              <label for="incident">Selecciona la incidencia relacionada con:</label>
              <select class="select2 form-control" multiple="multiple" data-placeholder="Select a Incident" style="width: 100%;" name="incident" id="incident">
                  <option value="Bot no responde">Bot no responde</option>
                  <option value="Configuracion en Campania">Configuración en Campaña</option>
                  <option value="Falla General de Plataforma">Falla General de Plataforma</option>
                  <option value="No ingresan interacciones a la plataforma">No ingresan interacciones a la plataforma</option>
                  <option value="Problemas con el envío de HSM(OutBound WhatsApp)">Problemas con el envío de HSM(OutBound WhatsApp)</option>
                  <option value="Problemas con videollamada">Problemas con videollamada</option>
              </select>
            </div>
            <div class="form-group">
              <label for="subject">¿Qué problema tienes?:</label>
              <input type="text" class="form-control" id="subject" name="subject" placeholder="" required>
            </div>
            <div class="form-group summernote-theme-1">
              <label for="description">Descripción del problema</label>
              <textarea rows="5" cols="33" class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
              <label for="afecta">¿A quién afecta la solicitud?</label>
              <select class="form-control" name="afecta" id="afecta" required>
                  <option value="A un usuario">A un usuario</option>
                  <option value="Algunos usuarios">Algunos usuarios</option>
                  <option value="Todos los usuarios">Todos los usuarios</option>
                  <option value="Al bot">Al bot</option>
              </select>
            </div>
            <div class="form-group">
              <label for="attachment">Archivo Adjunto</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="attachment" name="attachment">
                  <label class="custom-file-label" for="attachment">Elige un archivo</label>
                </div>
                <div class="input-group-append">
                  <span class="input-group-text">Upload</span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Registrar</button>
          </div>
        </form>
        <!-- Modal para mostrar el número de ticket asignado -->
        <div class="modal fade" id="modalNumeroTicket" tabindex="-1" aria-labelledby="modalNumeroTicketLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalNumeroTicketLabel">Registro Exitoso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                El número de ticket asignado es: <span id="numeroTicket"></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php require_once("../mainFooter/footer.php");?>
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
<script>
  new MultiSelectTag('channel');
  new MultiSelectTag('incident');
</script>
<script src="../../public/plugins/jquery/jquery.min.js"></script>
<script src="../../public/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
<script>
  document.getElementById('ticketForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const campaignName = document.getElementById('campaignName')?.value;
    const channels = Array.from(document.getElementById('channel')?.selectedOptions || []).map(option => option.value);
    const incidents = Array.from(document.getElementById('incident')?.selectedOptions || []).map(option => option.value);
    const subject = document.getElementById('subject')?.value;
    const description = document.getElementById('description')?.value;
    const afecta = document.getElementById('afecta')?.value;
    const attachment = document.getElementById('attachment')?.files[0];

    if (!campaignName || !subject || !description || !afecta) {
      alert('Por favor completa todos los campos requeridos');
      return;
    }

    const formData = new FormData();
    formData.append('campaignName', campaignName);
    formData.append('channels', channels.join(', '));
    formData.append('incidents', incidents.join(', '));
    formData.append('subject', subject);
    formData.append('description', description);
    formData.append('afecta', afecta);
    if(attachment){
      formData.append('attachment', attachment);
    }

    try {
      const response = await fetch('../../controller/proxyIncidence.php', {
        method: 'POST',
        body: formData
      });

      const responseData = await response.json();
      

      if (response.ok) {
        const ticketId = responseData.ticketId;
        document.getElementById('ticketForm').reset();
        mostrarModal(ticketId);
      } else {
        alert('Error al registrar el ticket: '+ responseData.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error al registrar el ticket');
    }
  });

  function mostrarModal(numeroTicket) {
    $('#modalNumeroTicket').modal('show');
    document.getElementById('numeroTicket').innerText = numeroTicket;
  }
</script>

<?php require_once("../mainJs/js.php");?>
</body>
</html>

<?php
  } else {
    header("Location:".Conn::ruta()."index.php");
  }
?>
