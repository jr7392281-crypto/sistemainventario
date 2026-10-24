<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/roles/listado_de_roles.php');
include('../app/controllers/areas/listado_de_areas.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de un nuevo usuario</h1>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>

                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">

                    <form action="../app/controllers/usuarios/create.php" method="post">
                      <div class="form-group">
                        <label for="">Nombres</label>
                        <input type="text" name="nombres" class="form-control" placeholder="Escriba su Nombre...."
                          required>
                      </div>
                      <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Escriba su Email...."
                          required>
                      </div>
                      <div class="form-group">
                        <label for="">Rol de Usuario</label>
                        <select name="rol" id="rol" class="form-control" required>
                          <option value="">Seleccione un rol</option>
                          <?php
                          foreach ($roles_datos as $roles_dato) { ?>
                            <option value="<?php echo (int) $roles_dato['id_rol']; ?>"><?php echo htmlspecialchars($roles_dato['rol'], ENT_QUOTES, 'UTF-8'); ?></option>

                            <?php
                          }
                          ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="id_area">Área</label>
                        <select name="id_area" id="id_area" class="form-control" required>
                          <option value="" selected disabled>Seleccione un área</option>
                          <?php foreach ($areas_datos as $area_dato) { ?>
                            <option value="<?php echo (int) $area_dato['id_area']; ?>"><?php echo htmlspecialchars($area_dato['nombre'], ENT_QUOTES, 'UTF-8'); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Contraseña</label>
                        <input type="password" name="password_user" class="form-control" minlength="8" required>
                      </div>
                      <div class="form-group">
                        <label for="">Repita su contraseña</label>
                        <input type="password" name="password_repeat" class="form-control" minlength="8" required>
                      </div>
                      <hr>
                      <div class="form-group">
                        <a href="index.php" class="btn btn-secondary">cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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

<!-- ****************************************************************** -->
<?php include('../layout/parte2.php'); ?>