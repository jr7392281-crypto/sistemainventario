<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Inventario y Mantenimiento</title>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <!-- Librería SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- DataTables-->
  <!-- DataTables -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

</head>

<body class="hold-transition sidebar-mini">

  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item-d-none-d-sm-inline-block">
          <a href="#" class="nav-link">SISTEMA DE INVENTARIO Y MANTENIMIENTO</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?php echo $URL; ?>" class="brand-link">
        <img src="<?php echo $URL; ?> /public/images/folder.png" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIS INV MAN</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo $URL; ?>/public/images/usuario.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo isset($nombres_sesion) ? $nombres_sesion : 'Usuario'; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <?php if ($rol_sesion === 'administrador') { ?>
             <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Usuarios
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/usuarios/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de usuarios</p>
                  </a>
                </li>
               
              </ul>
            </li>

            <!--roles--
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                  Roles
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/roles" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de Roles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/roles/create.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Creación de Roles</p>
                  </a>
                </li>
              </ul>
            </li>
             Fin de Roles -->

              <!--areas-->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-map-marker-alt"></i>
                <p>
                  Areas
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/areas/" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de áreas</p>
                  </a>
                </li>
                <!-- se muestra la creacion de area en el incio 
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/areas/create.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Registrar área</p>
                  </a>
                </li>
                -- Fin de Areas -->
              </ul>
            </li>
            <!-- Fin de Areas -->
            <?php } ?>

            <!--equipos-->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-desktop"></i>
                <p>
                  Equipos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/equipos" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de Equipos</p>
                  </a>
                </li>
                <?php if ($rol_sesion === 'administrador') { ?>
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/equipos/create.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Creación de Equipos</p>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <!-- Fin de Equipos -->
             <!--impresoras-->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-print"></i>
                <p>
                  Impresoras
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/impresoras" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de Impresoras</p>
                  </a>
                </li>
                <?php if ($rol_sesion === 'administrador') { ?>
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/impresoras/create.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Creación de Impresoras</p>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <!-- Fin de Impresoras -->
             <!--mantenimiento-->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tools"></i>
                <p>
                  Mantenimiento
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/mantenimiento" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de Mantenimiento</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/mantenimiento/create.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Creación de Mantenimiento</p>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Fin de Mantenimiento -->


            <!--cerrar sesion-->
            <li class="nav-item">
              <a href="<?php echo $URL; ?>/app/controllers/login/cerrar_sesion.php" class="nav-link"
                style="background-color: #ca0a0b">
                <i class="nav-icon fas fa-door-closed"></i>
                <p>
                  Cerrar Sesion
                </p>
              </a>
            </li>
          </ul>

        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>