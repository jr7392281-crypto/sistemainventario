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
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/css/sistema.css">
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

  
    <?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    include_once __DIR__ . '/mensajes.php';

    if (empty($_SESSION['csrf_notificaciones'])) {
      $_SESSION['csrf_notificaciones'] = bin2hex(random_bytes(32));
    }

    $notificaciones_recientes = [];
    $notificaciones_no_leidas = 0;

    $uri_actual = $_SERVER['REQUEST_URI'] ?? '';
    $ruta_actual = parse_url($uri_actual, PHP_URL_PATH) ?: '';
    $ruta_base = rtrim(parse_url($URL, PHP_URL_PATH) ?: '', '/');
    if ($ruta_base !== '' && strpos($ruta_actual, $ruta_base) === 0) {
      $ruta_actual = substr($ruta_actual, strlen($ruta_base));
    }
    if ($ruta_actual === '' || $ruta_actual[0] !== '/') {
      $ruta_actual = '/index.php';
    }
    $consulta_actual = parse_url($uri_actual, PHP_URL_QUERY);
    $retorno_notificaciones = $ruta_actual . ($consulta_actual ? '?' . $consulta_actual : '');

    if (isset($pdo, $id_usuario_sesion) && $pdo instanceof PDO) {
      try {
        $consulta_no_leidas = $pdo->prepare(
          'SELECT COUNT(*) FROM tb_notificaciones
           WHERE id_usuario_destino = :id_usuario AND leida = 0'
        );
        $consulta_no_leidas->execute([':id_usuario' => $id_usuario_sesion]);
        $notificaciones_no_leidas = (int) $consulta_no_leidas->fetchColumn();

        $consulta_notificaciones = $pdo->prepare(
          'SELECT n.id_notificacion, n.id_mantenimiento, n.titulo,
                  n.mensaje, n.leida, n.fecha_registro
           FROM tb_notificaciones AS n
           WHERE n.id_usuario_destino = :id_usuario
           ORDER BY n.fecha_registro DESC, n.id_notificacion DESC
           LIMIT 20'
        );
        $consulta_notificaciones->execute([':id_usuario' => $id_usuario_sesion]);
        $notificaciones_recientes = $consulta_notificaciones->fetchAll(PDO::FETCH_ASSOC);
      } catch (Throwable $e) {
        error_log('No se pudieron cargar las notificaciones: ' . $e->getMessage());
      }
    }
    ?>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">SISTEMA DE INVENTARIO Y MANTENIMIENTO</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a id="campana-notificaciones" class="nav-link" data-toggle="dropdown" href="#" role="button"
            aria-label="Notificaciones" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-bell"></i>
            <?php if ($notificaciones_no_leidas > 0) { ?>
              <span id="contador-notificaciones" class="badge badge-danger navbar-badge">
                <?php echo $notificaciones_no_leidas > 99 ? '99+' : $notificaciones_no_leidas; ?>
              </span>
            <?php } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0">
            <div class="px-3 py-2 d-flex justify-content-between align-items-center">
              <strong>Notificaciones</strong>
              <span id="texto-notificaciones-pendientes" class="text-muted small"><?php echo $notificaciones_no_leidas; ?> sin leer</span>
            </div>
            <?php if ($notificaciones_no_leidas > 0) { ?>
              <form action="<?php echo $URL; ?>/app/controllers/notificaciones/marcar_leida.php"
                method="post" class="js-marcar-notificacion px-3 pb-2 mb-0 text-right">
                <input type="hidden" name="csrf_token"
                  value="<?php echo htmlspecialchars($_SESSION['csrf_notificaciones'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="marcar_todas" value="1">
                <input type="hidden" name="return_to"
                  value="<?php echo htmlspecialchars($retorno_notificaciones, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-sm btn-link p-0">Marcar todas como leídas</button>
              </form>
            <?php } ?>
            <div class="dropdown-divider my-0"></div>
            <div style="max-height: 65vh; overflow-y: auto;">

            <?php if (empty($notificaciones_recientes)) { ?>
              <div class="dropdown-item text-muted text-center py-3">
                No tienes notificaciones
              </div>
            <?php } else { ?>
              <?php foreach ($notificaciones_recientes as $notificacion) { ?>
                <div class="dropdown-item text-wrap py-2 <?php echo (int) $notificacion['leida'] === 0 ? 'bg-light' : ''; ?>">
                  <div class="d-flex justify-content-between align-items-start">
                    <strong class="mr-2">
                      <i class="fas fa-bell mr-1 <?php echo (int) $notificacion['leida'] === 0 ? 'text-primary' : 'text-muted'; ?>"></i>
                      <?php echo htmlspecialchars($notificacion['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                    </strong>
                    <small class="text-muted text-nowrap">
                      <?php echo htmlspecialchars(date('d/m H:i', strtotime($notificacion['fecha_registro'])), ENT_QUOTES, 'UTF-8'); ?>
                    </small>
                  </div>
                  <div class="text-muted text-sm text-wrap">
                    <?php echo htmlspecialchars($notificacion['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-1">
                    <?php if (!empty($notificacion['id_mantenimiento'])) { ?>
                      <a class="small" href="<?php echo $URL; ?>/mantenimiento/show.php?id=<?php echo (int) $notificacion['id_mantenimiento']; ?>">
                        Ver mantenimiento
                      </a>
                    <?php } else { ?>
                      <span></span>
                    <?php } ?>
                    <?php if ((int) $notificacion['leida'] === 0) { ?>
                      <form action="<?php echo $URL; ?>/app/controllers/notificaciones/marcar_leida.php"
                        method="post" class="js-marcar-notificacion mb-0">
                        <input type="hidden" name="csrf_token"
                          value="<?php echo htmlspecialchars($_SESSION['csrf_notificaciones'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="id_notificacion"
                          value="<?php echo (int) $notificacion['id_notificacion']; ?>">
                        <input type="hidden" name="return_to"
                          value="<?php echo htmlspecialchars($retorno_notificaciones, ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary py-0">Marcar leída</button>
                      </form>
                    <?php } ?>
                  </div>
                </div>
                <div class="dropdown-divider"></div>
              <?php } ?>
            <?php } ?>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <script>
      document.addEventListener('submit', async function (event) {
        const formulario = event.target.closest('.js-marcar-notificacion');
        if (!formulario) return;

        event.preventDefault();
        const datos = new FormData(formulario);
        datos.append('ajax', '1');

        try {
          const respuesta = await fetch(formulario.action, {
            method: 'POST',
            body: datos,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const resultado = await respuesta.json();

          if (!respuesta.ok || !resultado.ok) {
            throw new Error(resultado.mensaje || 'No se pudo actualizar la notificación.');
          }

          if (datos.has('marcar_todas')) {
            document.querySelectorAll('.dropdown-item.bg-light').forEach(function (elemento) {
              elemento.classList.remove('bg-light');
              const icono = elemento.querySelector('.fa-bell');
              if (icono) {
                icono.classList.remove('text-primary');
                icono.classList.add('text-muted');
              }
              const boton = elemento.querySelector('.js-marcar-notificacion');
              if (boton) boton.remove();
            });
            formulario.remove();
          } else {
            const elemento = formulario.closest('.dropdown-item');
            if (elemento) {
              elemento.classList.remove('bg-light');
              const icono = elemento.querySelector('.fa-bell');
              if (icono) {
                icono.classList.remove('text-primary');
                icono.classList.add('text-muted');
              }
              formulario.remove();
            }
          }

          const pendientes = resultado.no_leidas;
          const textoPendientes = document.getElementById('texto-notificaciones-pendientes');
          if (textoPendientes) textoPendientes.textContent = pendientes + ' sin leer';

          let contador = document.getElementById('contador-notificaciones');
          if (pendientes === 0) {
            if (contador) contador.remove();
          } else {
            if (!contador) {
              contador = document.createElement('span');
              contador.id = 'contador-notificaciones';
              contador.className = 'badge badge-danger navbar-badge';
              document.getElementById('campana-notificaciones').appendChild(contador);
            }
            contador.textContent = pendientes > 99 ? '99+' : pendientes;
          }
        } catch (error) {
          if (window.Swal) {
            Swal.fire({ icon: 'error', title: error.message, showConfirmButton: false, timer: 1800 });
          }
        }
      });
    </script>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?php echo $URL; ?>" class="brand-link">
        <img src="<?php echo $URL; ?>/public/images/folder.png" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SIS INV MANT</span>
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

            <?php if (in_array($rol_sesion, ['administrador', 'super administrador'], true)) { ?>
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

              <!--roles-->
              <!-- <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>
                    Roles
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="/roles" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listado de Roles</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/roles/create.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Creación de Roles</p>
                    </a>
                  </li>
                </ul>
              </li> -->
              <!-- Fin de Roles -->
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
                <?php if (in_array($rol_sesion, ['administrador', 'super administrador', 'encargado de inventario'], true)) { ?>
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
                <?php if (in_array($rol_sesion, ['administrador', 'super administrador', 'encargado de inventario'], true)) { ?>
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

            <?php if (in_array($rol_sesion, ['administrador', 'super administrador', 'encargado de inventario', 'soporte tecnico'], true)) { ?>
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon fas fa-boxes"></i>
                  <p>
                    Componentes
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?php echo $URL; ?>/componentes/" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Stock de componentes</p>
                    </a>
                  </li>
                  <?php if (in_array($rol_sesion, ['administrador', 'super administrador', 'encargado de inventario'], true)) { ?>
                    <li class="nav-item">
                      <a href="<?php echo $URL; ?>/componentes/create.php" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Registrar componente</p>
                      </a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?php echo $URL; ?>/componentes/movimientos.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Uso de componentes</p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php } ?>

            <!--mantenimiento-->
            <?php if (in_array(strtolower(trim((string) $rol_sesion)), ['administrador', 'super administrador', 'personal', 'soporte tecnico'], true)) { ?>
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
                  <a href="<?php echo $URL; ?>/mantenimiento" class="nav-link"
                    style="display: flex; align-items: center;">
                    <i class="far fa-circle nav-icon" style="flex-shrink: 0;"></i>
                    <p style="flex: 1; min-width: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                      Listado de Mantenimiento
                    </p>
                  </a>
                </li>
                <?php if (strtolower(trim((string) $rol_sesion)) === 'personal') { ?>
                <li class="nav-item">
                  <a href="<?php echo $URL; ?>/mantenimiento/create.php" class="nav-link"
                    style="display: flex; align-items: center;">
                    <i class="far fa-circle nav-icon" style="flex-shrink: 0;"></i>
                    <p style="flex: 1; min-width: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                      Creación de Mantenimiento
                    </p>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>
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
