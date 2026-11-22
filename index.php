<?php
include('app/config.php');
include('layout/sesion.php');
include('app/controllers/equipos/listado_de_equipos.php');
include('app/controllers/impresoras/listado_impresoras.php');

$rol_actual = strtolower(trim((string) $rol_sesion));
$roles_con_mantenimientos = ['administrador', 'super administrador', 'personal', 'soporte tecnico'];
$mostrar_card_mantenimiento = in_array($rol_actual, $roles_con_mantenimientos, true);
$total_mantenimientos = 0;

if ($mostrar_card_mantenimiento) {
  $sql_total_mantenimientos = 'SELECT COUNT(*) FROM tb_mantenimientos';
  $parametros_mantenimiento = [];

  if ($rol_actual === 'personal') {
    $sql_total_mantenimientos .= ' WHERE id_usuario = :id_usuario';
    $parametros_mantenimiento[':id_usuario'] = (int) $id_usuario_sesion;
  } elseif ($rol_actual === 'soporte tecnico') {
    $sql_total_mantenimientos .= " WHERE estado IN ('enviado', 'rechazado')
      OR (estado = 'aceptado' AND id_tecnico_asignado IS NULL)
      OR id_tecnico_asignado = :id_tecnico";
    $parametros_mantenimiento[':id_tecnico'] = (int) $id_usuario_sesion;
  }

  $consulta_total_mantenimientos = $pdo->prepare($sql_total_mantenimientos);
  $consulta_total_mantenimientos->execute($parametros_mantenimiento);
  $total_mantenimientos = (int) $consulta_total_mantenimientos->fetchColumn();
}

if (in_array($rol_actual, ['administrador', 'super administrador'], true)) {
  include('app/controllers/usuarios/listado_de_usuarios.php');
  include('app/controllers/roles/listado_de_roles.php');
  include('app/controllers/areas/listado_de_areas.php');
}
include('layout/parte1.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Bienvenido al SISTEMA DE INVENTARIO Y MANTENIMIENTO - <?php echo $rol_sesion ?></h1>
          </h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      CONTENIDO DEL SISTEMA
      <br><br>

      <div class="row">

        <?php if (in_array($rol_actual, ['administrador', 'super administrador'], true)) { ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  <?php echo count($usuarios_datos); ?>
                </h3>
                <p>Usuarios Registrados</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="<?php echo $URL; ?>/usuarios/" class="small-box-footer">
                Más detalles <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  <?php echo count($roles_datos); ?>
                </h3>
                <p>Roles Registrados</p>
              </div>
              <div class="icon">
                <i class="fas fa-address-card"></i>
              </div>
              <a href="<?php echo $URL; ?>/roles/" class="small-box-footer">
                Más detalles <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  <?php echo count($areas_datos); ?>
                </h3>
                <p>Áreas Registradas</p>
              </div>
              <div class="icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <a href="<?php echo $URL; ?>/areas/" class="small-box-footer">
                Más detalles <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        <?php } ?>
        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-warning">
            <div class="inner">
              <?php
              $contador_de_equipos = 0;
              foreach ($equipos_datos as $equipos_dato) {
                $contador_de_equipos = $contador_de_equipos + 1;
              }
              ?>
              <H3><?php echo $contador_de_equipos ?></H3>
              <p>Equipos Registrados</p>
            </div>
            <div class="icon">
              <i class="fas fa-laptop"></i>
            </div>
            <a href="<?php echo $URL; ?>/equipos" class="small-box-footer">
              Mas detalles <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <?php
              $contador_de_impresoras = 0;
              foreach ($impresoras_datos as $impresoras_dato) {
                $contador_de_impresoras = $contador_de_impresoras + 1;
              }
              ?>
              <H3>
                <?php echo $contador_de_impresoras ?>
              </H3>
              <p>Impresoras Registradas</p>
            </div>
            <div class="icon">
              <i class="fas fa-print"></i>
            </div>
            <a href="<?php echo $URL; ?>/impresoras" class="small-box-footer">
              Mas detalles <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <?php if ($mostrar_card_mantenimiento) { ?>
        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <H3>
                <?php echo $total_mantenimientos; ?>
              </H3>
              <p>Mantenimientos Registrados</p>
            </div>
            <div class="icon">
              <i class="fas fa-wrench"></i>
            </div>
            <a href="<?php echo $URL; ?>/mantenimiento/" class="small-box-footer">
              Mas detalles <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <?php } ?>
      </div>

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('layout/parte2.php'); ?>
