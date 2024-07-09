<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../view/home" class="brand-link">
      <img src="../../public/images/icons/AdvanceSPC-Isotipo.png" alt="AdvanceSPC Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdvanceSPC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">
            <?php
            if(isset($_SESSION["us_name"]) && isset($_SESSION["us_ape"])){
              echo htmlspecialchars($_SESSION["us_name"] . " ". htmlspecialchars($_SESSION["us_ape"]));
            }else{
              echo "Usuario no encontrado";
            }
            ?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../home/index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../search/index.php" class="nav-link">
              <i class="nav-icon fas fa-search"></i>
              <p>
                Seguimiento
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../Logout/logout.php" class="nav-link">
              <i class="nav-icon fa fa-sign-out"></i>
              <p>
                Log Out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>