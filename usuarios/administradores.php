<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_super_administrador.php');
include('../app/controllers/usuarios/listado_administradores.php');
include('../app/controllers/areas/listado_de_areas.php');
include('../layout/parte1.php');
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Administradores</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Cuentas con rol administrador</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCrearAdministrador">
              <i class="fas fa-plus"></i> Crear administrador
            </button>
          </div>
        </div>

        <div class="card-body table-responsive">
          <table id="tablaAdministradores" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Nro</th>
                <th>Nombres</th>
                <th>Email</th>
                <th>Área</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php $contador = 0; ?>
              <?php foreach ($administradores_datos as $administrador) { ?>
                <tr>
                  <td><?php echo ++$contador; ?></td>
                  <td><?php echo htmlspecialchars($administrador['nombres'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($administrador['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($administrador['area'] ?? 'Sin área', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-nowrap">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                      data-target="#modalEditarAdministrador<?php echo (int) $administrador['id_usuario']; ?>">
                      <i class="fas fa-edit"></i> Editar
                    </button>
                    <form action="../app/controllers/usuarios/delete_administrador.php" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar esta cuenta de administrador?');">
                      <input type="hidden" name="id_usuario" value="<?php echo (int) $administrador['id_usuario']; ?>">
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Eliminar
                      </button>
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Crear administrador: el rol se asigna en el controlador, no desde el formulario. -->
<div class="modal fade" id="modalCrearAdministrador" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="fas fa-user-shield"></i> Crear administrador</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"><span>&times;</span></button>
      </div>
      <form action="../app/controllers/usuarios/create_administrador.php" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label>Nombres</label>
            <input type="text" name="nombres" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Área</label>
            <select name="id_area" class="form-control" required>
              <option value="">Seleccione un área</option>
              <?php foreach ($areas_datos as $area) { ?>
                <option value="<?php echo (int) $area['id_area']; ?>">
                  <?php echo htmlspecialchars($area['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="alert alert-info mb-0">Se asignará el rol administrador. Se enviará una clave temporal y el enlace de verificación al correo; deberá crear su propia contraseña al iniciar sesión.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edición de administradores existentes. El rol se conserva como administrador. -->
<?php foreach ($administradores_datos as $administrador) { ?>
  <div class="modal fade" id="modalEditarAdministrador<?php echo (int) $administrador['id_usuario']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white">Editar administrador</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"><span>&times;</span></button>
        </div>
        <form action="../app/controllers/usuarios/update_administrador.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="id_usuario" value="<?php echo (int) $administrador['id_usuario']; ?>">
            <div class="form-group">
              <label>Nombres</label>
              <input type="text" name="nombres" class="form-control" value="<?php echo htmlspecialchars($administrador['nombres'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($administrador['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
              <label>Área</label>
              <select name="id_area" class="form-control" required>
                <option value="">Seleccione un área</option>
                <?php foreach ($areas_datos as $area) { ?>
                  <option value="<?php echo (int) $area['id_area']; ?>" <?php echo ((int) $area['id_area'] === (int) $administrador['id_area']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($area['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label>Nueva contraseña (opcional)</label>
                <input type="password" name="password_user" class="form-control" minlength="8">
              </div>
              <div class="col-md-6 form-group">
                <label>Repetir nueva contraseña</label>
                <input type="password" name="password_repeat" class="form-control" minlength="8">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php } ?>

<?php include('../layout/parte2.php'); ?>
<script>
  $(function () {
    $('#tablaAdministradores').DataTable({
      responsive: true,
      autoWidth: false,
      pageLength: 5,
      order: [],
      language: {
        emptyTable: 'No hay administradores registrados',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ administradores',
        infoEmpty: 'Mostrando 0 administradores',
        infoFiltered: '(filtrado de _MAX_ administradores)',
        lengthMenu: 'Mostrar _MENU_ administradores',
        loadingRecords: 'Cargando...',
        processing: 'Procesando...',
        search: 'Buscador:',
        zeroRecords: 'No se encontraron administradores',
        paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
      }
    });
  });
</script>
