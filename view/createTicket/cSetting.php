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
              <li class="breadcrumb-item"><a href="../home/index.php">Home</a></li>
              <li class="breadcrumb-item active">Configuración</li>
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
              <input type="text" class="form-control" id="campaignName" placeholder="Empresa" required>
            </div>
            <div class="form-group">
              <label for="subject">Qué deseas implementar</label>
              <input type="text" class="form-control" id="subject" placeholder="" required>
            </div>
            <div class="form-group">
              <label for="description">Descripción del proyecto</label>
              <textarea rows="5" cols="33" class="form-control" id="description" required></textarea>
            </div>
            <div class="form-group">
              <label for="afecta">Tipo de Solicitud</label>
              <select class="form-control" name="incident" id="incident" required>
                  <option value="Un nuevo bot">Un nuevo bot</option>
                  <option value="Línea de WhatsApp">Línea de WhatsApp</option>
                  <option value="Nuevo Canal o Herramienta">Nuevo Canal o Herramienta</option>
                  <option value="Creación de Templates/HSM y/o modificación">Creación de Templates/HSM y/o modificación</option>
                  <option value="Creación de Usuarios Licencias">Creación de Usuarios Licencias</option>
                  <option value="Desactivar licencias o canales">Desactivar licencias o canales</option>
              </select>
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
    const subject = document.getElementById('subject')?.value;
    const description = document.getElementById('description')?.value;
    const incidents = Array.from(document.getElementById('incident')?.selectedOptions || []).map(option => option.value);
    const attachment = document.getElementById('attachment')?.files[0];
    
    if (!campaignName || !subject || !description || !incident) {
      alert('Por favor completa todos los campos requeridos');
      return;
    }

    const formData = new FormData();
    formData.append('campaignName', campaignName);
    formData.append('subject', subject);
    formData.append('description', description);
    formData.append('incidents', incidents.join(', '));
    if(attachment){
      formData.append('attachment', attachment);
    }
    
    try {
      const response = await fetch('../../controller/proxySetting.php', {
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