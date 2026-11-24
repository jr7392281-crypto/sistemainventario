<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_super_administrador.php');
include('../app/controllers/auditoria/listado_auditoria.php');
include('../layout/parte1.php');
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Auditoría del sistema</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Acciones registradas</h3>
        </div>
        <div class="card-body table-responsive">
          <table id="tablaAuditoria" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acción</th>
                <th>Módulo</th>
                <th>ID registro</th>
                <th>Descripción</th>
                <th>IP</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($auditoria_datos as $evento) { ?>
                <tr>
                  <td><?php echo htmlspecialchars($evento['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($evento['usuario_nombre'] ?? 'Usuario eliminado o sistema', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($evento['usuario_rol'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($evento['accion'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($evento['modulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo $evento['id_registro'] === null ? '-' : (int) $evento['id_registro']; ?></td>
                  <td><?php echo htmlspecialchars($evento['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($evento['ip'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include('../layout/parte2.php'); ?>
<script>
  $(function () {
    $('#tablaAuditoria').DataTable({
      responsive: true,
      autoWidth: false,
      pageLength: 10,
      order: [[0, 'desc']],
      language: {
        emptyTable: 'Todavía no hay acciones registradas',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ eventos',
        infoEmpty: 'Mostrando 0 eventos',
        infoFiltered: '(filtrado de _MAX_ eventos)',
        lengthMenu: 'Mostrar _MENU_ eventos',
        loadingRecords: 'Cargando...',
        processing: 'Procesando...',
        search: 'Buscar:',
        zeroRecords: 'No se encontraron eventos',
        paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
      }
    });
  });
</script>
