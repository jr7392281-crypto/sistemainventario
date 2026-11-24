
<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador_o_super_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/areas/listado_de_areas.php');
?>

<!-- ===================================================== -->
<!-- CONTENT WRAPPER -->
<!-- ===================================================== -->

<div class="content-wrapper">

    <!-- CONTENT HEADER -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de áreas</h1>
                </div>
            </div>

        </div>
    </div>
    <!-- /.content-header -->


    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="card card-outline card-primary">

                        <!-- CARD HEADER -->
                        <div class="card-header">

                            <h3 class="card-title">Áreas registradas</h3>

                            <div class="card-tools">

                                <!-- BOTÓN REGISTRAR -->
                                <button type="button"
                                    class="btn btn-primary btn-sm mr-2"
                                    data-toggle="modal"
                                    data-target="#modalRegistrarArea">

                                    <i class="fas fa-plus"></i> Registrar área

                                </button>

                                <button type="button" class="btn btn-tool"
                                    data-card-widget="collapse">

                                    <i class="fas fa-minus"></i>

                                </button>

                            </div>

                        </div>
                        <!-- /.card-header -->


                        <!-- CARD BODY -->
                        <div class="card-body" style="display:block">
                            <div class="table-responsive">

                            <table id="example1" class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th><center>Nro</center></th>
                                        <th><center>Nombre del área</center></th>
                                        <th><center>Descripci&oacute;n</center></th>
                                        <th><center>Acciones</center></th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $contador = 0;

                                    foreach ($areas_datos as $area_dato) {
                                    ?>

                                        <tr>

                                            <!-- NÚMERO -->
                                            <td>
                                                <center>
                                                    <?php echo ++$contador; ?>
                                                </center>
                                            </td>

                                            <!-- NOMBRE DEL ÁREA -->
                                            <td>
                                                <?php
                                                echo htmlspecialchars(
                                                    $area_dato['nombre'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                );
                                                ?>
                                            </td>

                                            <!-- DESCRIPCION DEL AREA -->
                                            <td>
                                                <?php
                                                $descripcion_area = trim((string) ($area_dato['descripcion'] ?? ''));
                                                echo $descripcion_area !== ''
                                                    ? htmlspecialchars($descripcion_area, ENT_QUOTES, 'UTF-8')
                                                    : '<span class="text-muted">Sin descripci&oacute;n</span>';
                                                ?>
                                            </td>

                                            <!-- ACCIONES -->
                                            <td>
                                                <center>

                                                    <!-- BOTÓN EDITAR -->
                                                    <button type="button"
                                                        class="btn btn-success btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalActualizarArea<?php echo (int) $area_dato['id_area']; ?>">

                                                        <i class="fas fa-pencil-alt"></i> Editar

                                                    </button>

                                                </center>
                                            </td>

                                        </tr>

                                    <?php
                                    }
                                    ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th><center>Nro</center></th>
                                        <th><center>Nombre del área</center></th>
                                        <th><center>Descripci&oacute;n</center></th>
                                        <th><center>Acciones</center></th>
                                    </tr>
                                </tfoot>

                            </table>
                            </div>

                        </div>
                        <!-- /.card-body -->

                    </div>

                </div>
            </div>

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->


<!-- ===================================================== -->
<!-- MODAL REGISTRAR ÁREA -->
<!-- ===================================================== -->

<div class="modal fade" id="modalRegistrarArea" tabindex="-1"
    role="dialog" aria-labelledby="tituloModalArea" aria-hidden="true">

    <div class="modal-dialog modal-md" role="document">

        <div class="modal-content">

            <!-- CABECERA -->
            <div class="modal-header bg-primary">

                <h5 class="modal-title" id="tituloModalArea">
                    <i class="fas fa-plus-circle"></i> Registrar nueva área
                </h5>

                <button type="button" class="close text-white"
                    data-dismiss="modal" aria-label="Cerrar">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <!-- FORMULARIO -->
            <form action="../app/controllers/areas/create.php" method="POST">

                <div class="modal-body">

                    <div class="form-group">

                        <label for="nombre">Nombre del área</label>

                        <input type="text"
                            id="nombre"
                            name="nombre"
                            class="form-control"
                            placeholder="Escriba el nombre del área"
                            maxlength="150"
                            required>

                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripci&oacute;n</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                            maxlength="255"
                            placeholder="Ej.: &Aacute;rea responsable de soporte y atenci&oacute;n a usuarios."
                            required></textarea>
                        <small class="form-text text-muted">Describe brevemente las funciones o servicios de esta &aacute;rea.</small>
                    </div>

                </div>

                <!-- PIE DEL MODAL -->
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">

                        <i class="fas fa-times"></i> Cancelar

                    </button>

                    <button type="submit" class="btn btn-primary">

                        <i class="fas fa-save"></i> Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- FIN MODAL REGISTRAR ÁREA -->


<!-- ===================================================== -->
<!-- MODALES ACTUALIZAR ÁREA -->
<!-- ===================================================== -->

<?php foreach ($areas_datos as $area_dato) { ?>

    <div class="modal fade"
        id="modalActualizarArea<?php echo (int) $area_dato['id_area']; ?>"
        tabindex="-1"
        role="dialog"
        aria-labelledby="tituloModalActualizarArea<?php echo (int) $area_dato['id_area']; ?>"
        aria-hidden="true">

        <div class="modal-dialog modal-md" role="document">

            <div class="modal-content">

                <!-- CABECERA VERDE -->
                <div class="modal-header bg-success">

                    <h5 class="modal-title text-white"
                        id="tituloModalActualizarArea<?php echo (int) $area_dato['id_area']; ?>">

                        <i class="fas fa-edit"></i> Actualizar área

                    </h5>

                    <button type="button" class="close text-white"
                        data-dismiss="modal" aria-label="Cerrar">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <!-- FORMULARIO -->
                <form action="../app/controllers/areas/update.php" method="POST">

                    <div class="modal-body">

                        <!-- ID DEL ÁREA -->
                        <input type="hidden"
                            name="id_area"
                            value="<?php echo (int) $area_dato['id_area']; ?>">

                        <!-- NOMBRE DEL ÁREA -->
                        <div class="form-group">

                            <label for="nombreActualizar<?php echo (int) $area_dato['id_area']; ?>">
                                Nombre del área
                            </label>

                            <input type="text"
                                id="nombreActualizar<?php echo (int) $area_dato['id_area']; ?>"
                                name="nombre"
                                class="form-control"
                                value="<?php echo htmlspecialchars(
                                    $area_dato['nombre'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                placeholder="Escriba el nombre del área"
                                maxlength="150"
                                required>

                        </div>

                        <div class="form-group">
                            <label for="descripcionActualizar<?php echo (int) $area_dato['id_area']; ?>">
                                Descripci&oacute;n
                            </label>
                            <textarea id="descripcionActualizar<?php echo (int) $area_dato['id_area']; ?>"
                                name="descripcion" class="form-control" rows="3" maxlength="255" required><?php
                                echo htmlspecialchars(
                                    (string) ($area_dato['descripcion'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?></textarea>
                        </div>

                    </div>

                    <!-- PIE DEL MODAL -->
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">

                            <i class="fas fa-times"></i> Cancelar

                        </button>

                        <!-- BOTÓN ACTUALIZAR VERDE -->
                        <button type="submit" class="btn btn-success">

                            <i class="fas fa-save"></i> Actualizar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

<?php } ?>

<!-- FIN MODALES ACTUALIZAR ÁREA -->


<!-- ===================================================== -->
<!-- MENSAJES DEL SISTEMA -->
<!-- ===================================================== -->

<?php include('../layout/mensajes.php'); ?>

<?php include('../layout/parte2.php'); ?>


<!-- ===================================================== -->
<!-- CONFIGURACIÓN DATATABLE -->
<!-- ===================================================== -->

<script>
    $(function () {

        $("#example1").DataTable({

            "pageLength": 5,

            language: {

                "emptyTable": "No hay información",
                "decimal": "",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ áreas",
                "infoEmpty": "Mostrando 0 a 0 de 0 áreas",
                "infoFiltered": "(Filtrado de _MAX_ total áreas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ áreas",
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

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>
