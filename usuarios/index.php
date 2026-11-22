<?php

include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/usuarios/listado_de_usuarios.php');
include('../app/controllers/roles/listado_de_roles.php');
include('../app/controllers/areas/listado_de_areas.php');

if (isset($_SESSION['mensaje'])) {
  $respuesta = $_SESSION['mensaje'];
  $icono_mensaje = $_SESSION['icono'] ?? 'success';
?>

  <script>
    Swal.fire({
      position: "top-end",
      icon: <?php echo json_encode($icono_mensaje); ?>,
      title: <?php echo json_encode($respuesta); ?>,
      showConfirmButton: false,
      timer: 1500
    });
  </script>

<?php
  unset($_SESSION['mensaje']);
  unset($_SESSION['icono']);
}
?>

<!-- CONTENT WRAPPER -->
<div class="content-wrapper">

  <!-- CONTENT HEADER -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Listado de usuarios</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-12">

          <div class="card card-outline card-primary">

            <div class="card-header">

              <h3 class="card-title">Usuarios Registrados</h3>

              <div class="card-tools">

                <!-- BOTÓN REGISTRAR -->
                <button type="button"
                  class="btn btn-primary btn-sm mr-2"
                  data-toggle="modal"
                  data-target="#modalRegistrarUsuario">

                  <i class="fas fa-plus"></i> Registrar usuario

                </button>

                <button type="button" class="btn btn-tool"
                  data-card-widget="collapse">

                  <i class="fas fa-minus"></i>

                </button>

              </div>

            </div>

            <div class="card-body" style="display:block">
              <div class="table-responsive">

              <table id="example1" class="table table-bordered table-striped">

                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Rol de usuario</center>
                    </th>
                    <th>
                      <center>Área</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $contador = 0;
                  $rol_sesion_normalizado = strtolower(trim((string) $rol_sesion));

                  foreach ($usuarios_datos as $usuario_dato) {

                    $id_usuario = $usuario_dato['id_usuario'];
                    $nombres_usuario = $usuario_dato['nombres'];
                    $email_usuario = $usuario_dato['email'];
                    $rol_usuario = $usuario_dato['rol'];
                    $area_usuario = $usuario_dato['area'] ?? '';
                    $rol_usuario_normalizado = strtolower(trim($rol_usuario));
                    $es_usuario_actual = strcasecmp($email_usuario, $_SESSION['sesion_email'] ?? '') === 0;
                    $es_superadministrador_objetivo = $rol_usuario_normalizado === 'super administrador';
                    $puede_editar_usuario = !(
                      $rol_sesion_normalizado === 'administrador'
                      && $es_superadministrador_objetivo
                    );
                    $puede_eliminar_usuario = !$es_usuario_actual && !(
                      $rol_sesion_normalizado === 'administrador'
                      && $es_superadministrador_objetivo
                    );
                  ?>

                    <tr>

                      <td>
                        <center><?php echo ++$contador; ?></center>
                      </td>

                      <td>
                        <?php echo htmlspecialchars($nombres_usuario, ENT_QUOTES, 'UTF-8'); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8'); ?>
                      </td>

                      <td>
                        <center>
                          <?php echo htmlspecialchars($rol_usuario, ENT_QUOTES, 'UTF-8'); ?>
                        </center>
                      </td>

                      <td>
                        <center>
                          <?php echo htmlspecialchars($area_usuario, ENT_QUOTES, 'UTF-8'); ?>
                        </center>
                      </td>

                      <td>
                        <center>

                          <div class="btn-group">

                            <!-- BOTÓN VER -->
                            <button type="button"
                              class="btn btn-info btn-sm"
                              data-toggle="modal"
                              data-target="#modalVerUsuario<?php echo $id_usuario; ?>">

                              <i class="fa fa-eye"></i> Ver

                            </button>

                            <!-- BOTÓN EDITAR -->
                            <?php if ($puede_editar_usuario) { ?>
                            <button type="button"
                              class="btn btn-success btn-sm"
                              data-toggle="modal"
                              data-target="#modalEditarUsuario<?php echo $id_usuario; ?>">

                              <i class="fa fa-pencil-alt"></i> Editar

                            </button>
                            <?php } ?>

                            <!-- BOTÓN BORRAR -->
                            <?php if ($puede_eliminar_usuario) { ?>
                              <button type="button"
                                class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#modalEliminarUsuario<?php echo (int) $id_usuario; ?>">
                                <i class="fas fa-trash"></i> Eliminar
                              </button>
                            <?php } ?>

                          </div>

                        </center>
                      </td>

                    </tr>



                    <!-- ================================================= -->
                    <!-- MODAL VER USUARIO -->
                    <!-- ================================================= -->

                    <div class="modal fade"
                      id="modalVerUsuario<?php echo $id_usuario; ?>"
                      tabindex="-1"
                      role="dialog"
                      aria-hidden="true">

                      <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">

                          <div class="modal-header bg-info">

                            <h5 class="modal-title text-white">
                              <i class="fas fa-user"></i>
                              Información del usuario
                            </h5>

                            <button type="button"
                              class="close text-white"
                              data-dismiss="modal">
                              <span>&times;</span>
                            </button>

                          </div>

                          <div class="modal-body">

                            <div class="row">

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Nombres completos</label>

                                  <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($nombres_usuario, ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Correo electrónico</label>

                                  <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                                </div>
                              </div>

                            </div>

                            <div class="row">

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Rol del usuario</label>

                                  <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($rol_usuario, ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Área asignada</label>

                                  <input type="text"
                                    class="form-control"
                                    value="<?php
                                            echo ($area_usuario !== '' && $area_usuario !== null)
                                              ? htmlspecialchars($area_usuario, ENT_QUOTES, 'UTF-8')
                                              : 'Sin área asignada';
                                            ?>"
                                    readonly>
                                </div>
                              </div>

                            </div>

                            <div class="form-group">

                              <label>Descripción del rol</label>

                              <textarea class="form-control"
                                rows="2"
                                readonly><?php
                                          $rol_normalizado = mb_strtolower(trim($rol_usuario), 'UTF-8');

                                          $rol_normalizado = strtr($rol_normalizado, [
                                            'á' => 'a',
                                            'é' => 'e',
                                            'í' => 'i',
                                            'ó' => 'o',
                                            'ú' => 'u'
                                          ]);

                                          $descripciones_roles = [
                                            'administrador' => 'Gestiona reportes y operaciones. No puede modificar ni eliminar super administradores.',
                                            'encargado de inventario' => 'Gestiona equipos e impresoras y registra componentes con su stock inicial.',
                                            'super administrador' => 'Tiene acceso total y puede gestionar a los administradores y demas usuarios.',
                                            'abastecedor' => 'Consulta equipos e impresoras; no administra componentes ni mantenimientos.',
                                            'soporte tecnico' => 'Atiende trabajos asignados, registra componentes usados y reporta la solucion.',
                                            'personal' => 'Registra solicitudes de mantenimiento y consulta sus propias solicitudes.'
                                          ];

                                          echo htmlspecialchars(
                                            $descripciones_roles[$rol_normalizado] ?? 'Rol del sistema municipal.',
                                            ENT_QUOTES,
                                            'UTF-8'
                                          );
                                          ?></textarea>

                            </div>

                          </div>

                          <div class="modal-footer">

                            <button type="button"
                              class="btn btn-secondary"
                              data-dismiss="modal">

                              <i class="fas fa-times"></i> Cerrar

                            </button>

                          </div>

                        </div>

                      </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- MODAL EDITAR USUARIO -->
                    <!-- ================================================= -->

                    <div class="modal fade"
                      id="modalEditarUsuario<?php echo $id_usuario; ?>"
                      tabindex="-1"
                      role="dialog"
                      aria-hidden="true">

                      <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">

                          <div class="modal-header bg-primary">

                            <h5 class="modal-title text-white">
                              <i class="fas fa-user-edit"></i>
                              Editar usuario
                            </h5>

                            <button type="button"
                              class="close text-white"
                              data-dismiss="modal">

                              <span>&times;</span>

                            </button>

                          </div>

                          <form action="../app/controllers/usuarios/update.php"
                            method="POST">

                            <div class="modal-body">

                              <input type="hidden"
                                name="id_usuario"
                                value="<?php echo (int) $id_usuario; ?>">

                              <div class="row">

                                <div class="col-md-6">

                                  <div class="form-group">

                                    <label>Nombres completos</label>

                                    <input type="text"
                                      name="nombres"
                                      class="form-control"
                                      value="<?php echo htmlspecialchars($nombres_usuario, ENT_QUOTES, 'UTF-8'); ?>"
                                      required>

                                  </div>

                                </div>

                                <div class="col-md-6">

                                  <div class="form-group">

                                    <label>Correo electrónico</label>

                                    <input type="email"
                                      name="email"
                                      class="form-control"
                                      value="<?php echo htmlspecialchars($email_usuario, ENT_QUOTES, 'UTF-8'); ?>"
                                      required>

                                  </div>

                                </div>

                              </div>

                              <div class="form-group">

                                <label>
                                  <i class="fas fa-user-tag"></i>
                                  Rol de usuario
                                </label>

                                <select name="rol"
                                  class="form-control select-rol-editar"
                                  required>

                                  <option value="">Seleccione un rol</option>

                                  <?php foreach ($roles_datos as $roles_dato) {

                                    $nombre_rol_opcion = $roles_dato['rol'];
                                    if ($rol_sesion_normalizado === 'administrador' && strtolower(trim($nombre_rol_opcion)) === 'super administrador') {
                                      continue;
                                    }

                                  ?>

                                    <option
                                      value="<?php echo (int) $roles_dato['id_rol']; ?>"
                                      <?php echo (strcasecmp(trim($nombre_rol_opcion), trim($rol_usuario)) === 0) ? 'selected' : ''; ?>>

                                      <?php echo htmlspecialchars($nombre_rol_opcion, ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                  <?php } ?>

                                </select>

                              </div>

                              <div class="form-group">

                                <label>Descripción del rol</label>

                                <div class="alert alert-info mb-0 descripcion-rol-editar">

                                  <i class="fas fa-info-circle"></i>

                                  <span class="texto-descripcion-editar"></span>

                                </div>

                              </div>

                              <div class="form-group grupo-area-editar">

                                <label>
                                  <i class="fas fa-building"></i>
                                  Área de trabajo
                                </label>

                                <select name="id_area"
                                  class="form-control selector-area-editar"
                                  required>

                                  <option value="">Seleccione un área</option>

                                  <?php foreach ($areas_datos as $area_dato) {

                                    $nombre_area_opcion = $area_dato['nombre'];

                                  ?>

                                    <option
                                      value="<?php echo (int) $area_dato['id_area']; ?>"
                                      <?php echo (strcasecmp(trim($nombre_area_opcion), trim($area_usuario)) === 0) ? 'selected' : ''; ?>>

                                      <?php echo htmlspecialchars($nombre_area_opcion, ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                  <?php } ?>

                                </select>

                              </div>

                              <hr>

                              <div class="alert alert-secondary">

                                <i class="fas fa-info-circle"></i>

                                Si no deseas cambiar la contraseña, deja los siguientes campos vacíos.

                              </div>

                              <div class="row">

                                <div class="col-md-6">

                                  <div class="form-group">

                                    <label>Nueva contraseña</label>

                                    <input type="password"
                                      name="password_user"
                                      class="form-control"
                                      minlength="8"
                                      placeholder="Ingrese una nueva contraseña">

                                  </div>

                                </div>

                                <div class="col-md-6">

                                  <div class="form-group">

                                    <label>Confirmar nueva contraseña</label>

                                    <input type="password"
                                      name="password_repeat"
                                      class="form-control"
                                      minlength="8"
                                      placeholder="Repita la nueva contraseña">

                                  </div>

                                </div>

                              </div>

                            </div>

                            <div class="modal-footer">

                              <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">

                                <i class="fas fa-times"></i> Cancelar

                              </button>

                              <button type="submit"
                                class="btn btn-success">

                                <i class="fas fa-save"></i> Guardar cambios

                              </button>

                            </div>

                          </form>

                        </div>

                      </div>

                    </div>

                  <?php } ?>

                </tbody>

                <tfoot>

                  <tr>

                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Rol de usuario</center>
                    </th>
                    <th>
                      <center>Área</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>

                  </tr>

                </tfoot>

              </table>
              </div>

            </div>

          </div>

        </div>
      </div>

    </div>
  </div>

</div>
<!-- MODAL ELIMINAR USUARIO -->
<?php foreach ($usuarios_datos as $usuario_dato) {
    $id_usuario_modal = (int) $usuario_dato['id_usuario'];
    $email_modal = $usuario_dato['email'];
    $rol_modal = strtolower(trim($usuario_dato['rol']));
    $es_usuario_actual_modal = strcasecmp($email_modal, $_SESSION['sesion_email'] ?? '') === 0;
    $puede_eliminar_modal = !$es_usuario_actual_modal && !(
      $rol_sesion_normalizado === 'administrador'
      && $rol_modal === 'super administrador'
    );

    if (!$puede_eliminar_modal) {
      continue;
    }
?>
<div class="modal fade" id="modalEliminarUsuario<?php echo $id_usuario_modal; ?>" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmar eliminación
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <i class="fas fa-user-times text-danger"
                   style="font-size: 55px; margin-bottom: 15px;"></i>

                <h5>¿Estás seguro de eliminar este usuario?</h5>

                <p class="text-muted mb-0">
                    Usuario: <strong><?php echo htmlspecialchars($usuario_dato['nombres'], ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>

                <p class="text-danger mt-2 mb-0">
                    Esta acción no se puede deshacer.
                </p>

            </div>

            <div class="modal-footer justify-content-center">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>

                <form action="../app/controllers/usuarios/delete.php" method="POST">

                    <input type="hidden" name="id_usuario"
                           value="<?php echo $id_usuario_modal; ?>">

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sí, eliminar
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
<?php } ?>
<!-- FIN CONTENT WRAPPER -->


<!-- ====================================================== -->
<!-- MODAL REGISTRAR USUARIO -->
<!-- ====================================================== -->

<div class="modal fade" id="modalRegistrarUsuario" tabindex="-1"
  role="dialog" aria-labelledby="tituloModalUsuario" aria-hidden="true">

  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header bg-primary">

        <h5 class="modal-title text-white" id="tituloModalUsuario">
          <i class="fas fa-user-plus"></i>
          Registrar nuevo usuario
        </h5>

        <button type="button" class="close text-white"
          data-dismiss="modal" aria-label="Cerrar">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="../app/controllers/usuarios/create.php" method="POST">

        <div class="modal-body">

          <div class="row">

            <div class="col-md-6">

              <div class="form-group">

                <label>Nombres</label>

                <input type="text"
                  name="nombres"
                  class="form-control"
                  placeholder="Escriba los nombres del usuario"
                  required>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                <label>Email</label>

                <input type="email"
                  name="email"
                  class="form-control"
                  placeholder="Escriba el correo electrónico"
                  required>

              </div>

            </div>

          </div>

          <div class="form-group">

            <label>
              <i class="fas fa-user-tag"></i>
              Rol de usuario
            </label>

            <select name="rol"
              id="rol"
              class="form-control"
              required>

              <option value="">Seleccione un rol</option>

              <?php foreach ($roles_datos as $roles_dato) {
                if ($rol_sesion_normalizado === 'administrador' && strtolower(trim($roles_dato['rol'])) === 'super administrador') {
                  continue;
                }
              ?>

                <option value="<?php echo (int) $roles_dato['id_rol']; ?>">

                  <?php echo htmlspecialchars($roles_dato['rol'], ENT_QUOTES, 'UTF-8'); ?>

                </option>

              <?php } ?>

            </select>

          </div>

          <div id="descripcionRol" class="alert alert-info"
            style="display:none;">

            <i class="fas fa-info-circle"></i>

            <strong>Descripción del rol:</strong>

            <span id="textoDescripcionRol"></span>

          </div>

          <div class="form-group" id="contenedorArea">

            <label>
              <i class="fas fa-building"></i>
              Área de trabajo
            </label>

            <select name="id_area"
              id="id_area"
              class="form-control"
              required>

              <option value="">Seleccione un área</option>

              <?php foreach ($areas_datos as $area_dato) { ?>

                <option value="<?php echo (int) $area_dato['id_area']; ?>">

                  <?php echo htmlspecialchars($area_dato['nombre'], ENT_QUOTES, 'UTF-8'); ?>

                </option>

              <?php } ?>

            </select>

            <small class="text-muted">
              Seleccione el área donde trabajará el personal.
            </small>

          </div>

          <hr>

          <div class="row">

            <div class="col-md-6">

              <div class="form-group">

                <label>Contraseña</label>

                <input type="password"
                  name="password_user"
                  id="password_user"
                  class="form-control"
                  placeholder="Ingrese una contraseña"
                  minlength="8"
                  required>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                <label>Repita su contraseña</label>

                <input type="password"
                  name="password_repeat"
                  id="password_repeat"
                  class="form-control"
                  placeholder="Repita la contraseña"
                  minlength="8"
                  required>

              </div>

            </div>

          </div>

          <div class="alert alert-secondary mb-0">

            <i class="fas fa-shield-alt"></i>

            La contraseña debe contener como mínimo 8 caracteres.

          </div>

        </div>

        <div class="modal-footer">

          <button type="button"
            class="btn btn-secondary"
            data-dismiss="modal">

            <i class="fas fa-times"></i> Cancelar

          </button>

          <button type="submit" class="btn btn-primary">

            <i class="fas fa-save"></i> Guardar usuario

          </button>

        </div>

      </form>

    </div>

  </div>

</div>


<!-- MENSAJES -->
<?php include('../layout/mensajes.php'); ?>

<?php include('../layout/parte2.php'); ?>


<!-- ====================================================== -->
<!-- JAVASCRIPT DE LOS MODALES Y DATATABLES -->
<!-- ====================================================== -->

<script>
  $(document).ready(function() {

    // ==========================================
    // DESCRIPCIONES DE LOS ROLES
    // ==========================================

    const descripciones = {
      'administrador': 'Gestiona reportes y operaciones. No puede modificar ni eliminar super administradores.',
      'encargado de inventario': 'Gestiona equipos e impresoras y registra componentes con su stock inicial.',
      'super administrador': 'Tiene acceso total y puede gestionar a los administradores y demas usuarios.',
      'abastecedor': 'Consulta equipos e impresoras; no administra componentes ni mantenimientos.',
      'soporte tecnico': 'Atiende trabajos asignados, registra componentes usados y reporta la solucion.',
      'personal': 'Registra solicitudes de mantenimiento y consulta sus propias solicitudes.'
    };


    // ==========================================
    // NORMALIZAR NOMBRES DE ROLES
    // ==========================================

    function normalizarTexto(texto) {

      return texto
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();

    }


    // ==========================================
    // MODAL REGISTRAR USUARIO
    // ==========================================

    const selectorRol = document.getElementById('rol');
    const contenedorArea = document.getElementById('contenedorArea');
    const selectorArea = document.getElementById('id_area');
    const descripcionRol = document.getElementById('descripcionRol');
    const textoDescripcionRol = document.getElementById('textoDescripcionRol');


    function actualizarRolRegistro() {

      if (!selectorRol || !contenedorArea || !selectorArea) {
        return;
      }

      const opcion = selectorRol.options[selectorRol.selectedIndex];

      const nombreRol = normalizarTexto(opcion.text);


      // DESCRIPCIÓN DEL ROL

      if (selectorRol.value !== '') {

        if (textoDescripcionRol && descripcionRol) {

          textoDescripcionRol.textContent =
            descripciones[nombreRol] || 'Rol del sistema municipal.';

          descripcionRol.style.display = 'block';

        }

      } else {

        if (descripcionRol) {
          descripcionRol.style.display = 'none';
        }

      }


      // Todos los roles requieren un área.

      contenedorArea.style.display = 'block';
      selectorArea.disabled = false;
      selectorArea.required = true;

    }


    if (selectorRol) {

      selectorRol.addEventListener('change', actualizarRolRegistro);

      actualizarRolRegistro();

    }


    // ==========================================
    // LIMPIAR FORMULARIO DE REGISTRO
    // ==========================================

    $('#modalRegistrarUsuario').on('hidden.bs.modal', function() {

      const formulario = this.querySelector('form');

      if (formulario) {
        formulario.reset();
      }

      actualizarRolRegistro();

    });


    // ==========================================
    // VALIDAR CONTRASEÑAS DE REGISTRO
    // ==========================================

    $('#modalRegistrarUsuario form').on('submit', function(event) {

      const password = this.querySelector('[name="password_user"]').value;

      const passwordRepeat = this.querySelector('[name="password_repeat"]').value;


      if (password !== passwordRepeat) {

        event.preventDefault();

        Swal.fire({
          icon: 'error',
          title: 'Contraseñas diferentes',
          text: 'Las contraseñas ingresadas no coinciden.'
        });

      }

    });


    // ==========================================
    // MODALES EDITAR USUARIO
    // ==========================================

    function actualizarRolEdicion(modal) {

      const selectRol = modal.querySelector('.select-rol-editar');

      const grupoArea = modal.querySelector('.grupo-area-editar');

      const selectArea = modal.querySelector('.selector-area-editar');

      const descripcion = modal.querySelector('.texto-descripcion-editar');


      if (!selectRol || !grupoArea || !selectArea || !descripcion) {
        return;
      }


      const opcion = selectRol.options[selectRol.selectedIndex];

      const nombreRol = normalizarTexto(opcion.text);


      // ==========================================
      // ACTUALIZAR DESCRIPCIÓN DEL ROL
      // ==========================================

      if (selectRol.value !== '') {

        descripcion.textContent =
          descripciones[nombreRol] || 'Rol del sistema municipal.';

      } else {

        descripcion.textContent = '';

      }


      // ==========================================
      // Todos los roles requieren un área.
      // ==========================================

      grupoArea.style.display = 'block';
      selectArea.disabled = false;
      selectArea.required = true;

    }


    // ==========================================
    // DETECTAR CAMBIO DE ROL EN EDICIÓN
    // ==========================================

    $(document).on('change', '.select-rol-editar', function() {

      const modal = this.closest('.modal');

      if (modal) {

        actualizarRolEdicion(modal);

      }

    });


    // ==========================================
    // INICIALIZAR TODOS LOS MODALES DE EDICIÓN
    // ==========================================

    $('[id^="modalEditarUsuario"]').each(function() {

      actualizarRolEdicion(this);

    });


    // ==========================================
    // INICIALIZAR AL ABRIR EL MODAL
    // ==========================================

    $('[id^="modalEditarUsuario"]').on('shown.bs.modal', function() {

      actualizarRolEdicion(this);

    });


    // ==========================================
    // VALIDAR CONTRASEÑAS DE EDICIÓN
    // ==========================================

    $('[id^="modalEditarUsuario"] form').on('submit', function(event) {

      const password = this.querySelector('[name="password_user"]').value;

      const passwordRepeat = this.querySelector('[name="password_repeat"]').value;


      // VALIDAR QUE LAS CONTRASEÑAS COINCIDAN

      if (password !== passwordRepeat) {

        event.preventDefault();

        Swal.fire({
          icon: 'error',
          title: 'Contraseñas diferentes',
          text: 'Las nuevas contraseñas no coinciden.'
        });

        return;

      }


      // VALIDAR LONGITUD MÍNIMA

      if (
        (password !== '' && password.length < 8) ||
        (passwordRepeat !== '' && passwordRepeat.length < 8)
      ) {

        event.preventDefault();

        Swal.fire({
          icon: 'warning',
          title: 'Contraseña muy corta',
          text: 'La nueva contraseña debe tener como mínimo 8 caracteres.'
        });

      }

    });


    // ==========================================
    // DATATABLES
    // ==========================================

    $("#example1").DataTable({

      "pageLength": 5,

      language: {

        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
        "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
        "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Usuarios",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscador:",
        "zeroRecords": "Sin resultados encontrados",

        "paginate": {

          "first": "Primero",
          "last": "Último",
          "next": "Siguiente",
          "previous": "Anterior"

        }

      },

      "responsive": true,

      "lengthChange": true,

      "autoWidth": false,

      buttons: [

        {

          extend: 'collection',

          text: 'Reportes',

          orientation: 'landscape',

          buttons: [

            {
              text: 'Copiar',
              extend: 'copy'
            },

            {
              extend: 'pdf'
            },

            {
              extend: 'csv'
            },

            {
              extend: 'excel'
            },

            {
              text: 'Imprimir',
              extend: 'print'
            }

          ]

        },

        {

          extend: 'colvis',

          text: 'Visor de columnas',

          collectionLayout: 'fixed three-column'

        }

      ]

    }).buttons().container().appendTo(
      '#example1_wrapper .col-md-6:eq(0)'
    );


  });
</script>
