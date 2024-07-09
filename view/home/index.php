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
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <?php require_once("../mainHeader/header.php");?>

  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php require_once("../mainNav/nav.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Página Principal</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-6">
              <a href="../createTicket/cIncidence.php">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h5 class="m-0">Incidencias</h5>
                  </div>
                  <div class="card-body">
                    <p class="card-text">Para reportar un problema o error en la configuración.</p>
                  </div>
                </div>
              </a>
              <a href="../createTicket/cSetting.php">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h5 class="m-0">Configuración</h5>
                  </div>
                  <div class="card-body">
                    <p class="card-text">Solicitar configuraciones adicionales, aprobación de HSM, solicitar capacitaciones.</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-lg-6">
            <a href="../createTicket/cProject.php">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h5 class="m-0">Proyectos</h5>
                </div>
                <div class="card-body">
                  <p class="card-text">Requieres de un nuevo proyecto o desarrollo.</p>
                </div>
              </div>
              </a>
            </div>
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php  require_once("../mainFooter/footer.php");?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php  require_once("../mainJs/js.php");?>
</body>
</html>

<?php
  } else {
    header("Location:".Conn::ruta()."index.php");
  }
?>