<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/areas/listado_de_areas.php');



?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
                    <h1 class="m-0">Listado de áreas</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-8">

          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Áreas registradas</h3>
              <div class="card-tools">
                <a href="create.php" class="btn btn-primary btn-sm mr-2"><i class="fas fa-plus"></i> Registrar área</a>
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display:block">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombre del área</center>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $contador = 0;
                  foreach ($areas_datos as $area_dato) {
                    ?>
                    <tr>
                      <td>
                        <center><?php echo $contador = $contador + 1; ?> </center>
                      </td>
                      <td><?php echo htmlspecialchars($area_dato['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <center>
                          <a href="update.php?id=<?php echo $area_dato['id_area']; ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </a>
                        </center>
                      </td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombre del área</center>
                    </th>
                  </tr>
                </tfoot>
              </table>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>



    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('../layout/mensajes.php') ?>
<?php include('../layout/parte2.php'); ?>

<script>
  $(function () {
    $("#example1").DataTable({
      /* cambio de idiomas de datatable */
      "pageLength": 5,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ áreas",
        "infoEmpty": "Mostrando 0 a 0 de 0 áreas",
        "infoFiltered": "(Filtrado de MAX total áreas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ áreas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscador:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      },
      /* fin de idiomas */
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      buttons: [{
        extend: 'collection',
        text: 'Reportes',
        orientation: 'landscape',
        buttons: [{
          text: 'Copiar',
          extend: 'copy',
        }, {
          extend: 'pdf'
        }, {
          extend: 'csv'
        }, {
          extend: 'excel'
        }, {
          text: 'Imprimir',
          extend: 'print'
        }]
      },
      {
        extend: 'colvis',
        text: 'Visor de columnas',
        collectionLayout: 'fixed three-column'
      }
      ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>