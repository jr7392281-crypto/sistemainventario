<?php
include('app/config.php');
include('layout/sesion.php');
include('layout/parte1.php');
include('app/controllers/equipos/listado_de_equipos.php');
include('app/controllers/impresoras/listado_impresoras.php');
include('app/controllers/mantenimiento/listado_mantenimientos.php');
if ($rol_sesion === 'administrador') {
  include('app/controllers/usuarios/listado_de_usuarios.php');
  include('app/controllers/roles/listado_de_roles.php');
  include('app/controllers/areas/listado_de_areas.php');
}
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

        <?php if ($rol_sesion === 'administrador') { ?>
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

        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <?php
              $contador_de_mantenimientos = 0;
              foreach ($mantenimientos_datos as $mantenimientos_dato) {
                $contador_de_mantenimientos = $contador_de_mantenimientos + 1;
              }
              ?>
              <H3>
                <?php echo $contador_de_mantenimientos ?>
              </H3>
              <p>Mantenimientos Registrados</p>
            </div>
            <a href="<?php echo $URL; ?>/mantenimiento/create.php">
              <div class="icon">
                <i class="fas fa-wrench"></i>
              </div>
            </a>
            <a href="<?php echo $URL; ?>/mantenimiento/" class="small-box-footer">
              Mas detalles <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('layout/parte2.php'); ?>