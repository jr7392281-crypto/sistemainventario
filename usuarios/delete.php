<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/usuarios/show_usuario.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Eliminar usuario</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-5">
          <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">¿Esta seguro de eliminar al usuario?</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>

            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">

                  <form action="../app/controllers/usuarios/delete.php" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo (int) $id_usuario_get; ?>">
                    <div class="form-group">
                      <label for="">Nombres</label>
                      <input type="text" name="nombres" class="form-control" value="<?php echo $nombres; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Rol de usuario</label>
                      <input type="text" name="rol" class="form-control" value="<?php echo $rol; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Area de Usuario</label>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($area, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                    </div>
                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Volver</a>
                      <button class="btn btn-danger">Eliminar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('../layout/parte2.php'); ?>