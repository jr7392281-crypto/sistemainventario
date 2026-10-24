<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/equipos/listado_de_equipos.php');
?>

<!-- Content Wrapper -->
<div class="content-wrapper">

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de equipos</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">

                    <div class="card card-outline card-primary shadow-sm">

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-desktop mr-2"></i>
                                Equipos registrados
                            </h3>

                            <div class="card-tools">
                                <button type="button"
                                        class="btn btn-tool"
                                        data-card-widget="collapse"
                                        title="Contraer">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            <!-- Tabla principal -->
                            <div class="table-responsive">

                                <table id="example1"
                                       class="table table-hover table-bordered align-middle"
                                       style="width:100%;">

                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width:55px;">Nro</th>
                                            <th style="min-width:120px;">Código</th>
                                            <th style="min-width:190px;">Dispositivo</th>
                                            <th style="min-width:170px;">Procesador</th>
                                            <th class="text-center" style="min-width:80px;">RAM</th>
                                            <th style="min-width:150px;">Sistema operativo</th>
                                            <th style="min-width:120px;">Almacenamiento</th>
                                            <th class="text-center" style="min-width:120px;">Estado</th>
                                            <th class="text-center" style="min-width:80px;">Área</th>
                                            <th class="text-center" style="min-width:170px;">Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php
                                    $contador = 0;

                                    foreach ($equipos_datos as $equipos_dato) {

                                        $id_equipo = $equipos_dato['id_equipo'];

                                        $codigo = htmlspecialchars($equipos_dato['codigo'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $nombre_dispositivo = htmlspecialchars($equipos_dato['nombre_dispositivo'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $procesador = htmlspecialchars($equipos_dato['procesador'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $ram = htmlspecialchars($equipos_dato['ram'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $tarjeta_grafica = htmlspecialchars($equipos_dato['tarjeta_grafica'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $sistema_operativo = htmlspecialchars($equipos_dato['sistema_operativo'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $almacenamiento = htmlspecialchars($equipos_dato['almacenamiento'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $monitor = htmlspecialchars($equipos_dato['monitor'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $ip = htmlspecialchars($equipos_dato['ip'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $mouse = htmlspecialchars($equipos_dato['mouse'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $teclado = htmlspecialchars($equipos_dato['teclado'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $area = htmlspecialchars($equipos_dato['area'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $observacion = htmlspecialchars($equipos_dato['observacion'] ?? '', ENT_QUOTES, 'UTF-8');
                                        $fecha_registro = htmlspecialchars($equipos_dato['fecha_registro'] ?? '', ENT_QUOTES, 'UTF-8');
                                    ?>

                                        <tr>

                                            <td class="text-center font-weight-bold">
                                                <?php echo ++$contador; ?>
                                            </td>

                                            <td>
                                                <span class="codigo-equipo">
                                                    <?php echo $codigo; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="font-weight-bold">
                                                    <?php echo $nombre_dispositivo; ?>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="dato-tabla" title="<?php echo $procesador; ?>">
                                                    <?php echo $procesador; ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-secondary px-2 py-1">
                                                    <?php echo $ram; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="dato-tabla" title="<?php echo $sistema_operativo; ?>">
                                                    <?php echo $sistema_operativo; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?php echo $almacenamiento; ?>
                                            </td>

                                            <td class="text-center">

                                                <?php if ($equipos_dato['estado'] == "Operativo") { ?>

                                                    <span class="badge badge-success estado-badge">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Operativo
                                                    </span>

                                                <?php } else if ($equipos_dato['estado'] == "Mantenimiento") { ?>

                                                    <span class="badge badge-warning estado-badge">
                                                        <i class="fas fa-tools mr-1"></i>
                                                        Mantenimiento
                                                    </span>

                                                <?php } else { ?>

                                                    <span class="badge badge-danger estado-badge">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        <?php echo htmlspecialchars($equipos_dato['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>

                                                <?php } ?>

                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-light border px-2 py-1">
                                                    <?php echo $area; ?>
                                                </span>
                                            </td>

                                            <td class="text-center">

                                                <div class="btn-group acciones" role="group">

                                                    <a href="show.php?id=<?php echo $id_equipo; ?>"
                                                       class="btn btn-info btn-sm"
                                                       title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                        Ver
                                                    </a>

                                                    <?php if ($rol_sesion === 'administrador') { ?>
                                                        <a href="update.php?id=<?php echo $id_equipo; ?>"
                                                           class="btn btn-success btn-sm"
                                                           title="Editar equipo">
                                                            <i class="fas fa-edit"></i>
                                                            Editar
                                                        </a>

                                                        <a href="delete.php?id=<?php echo $id_equipo; ?>"
                                                           class="btn btn-danger btn-sm"
                                                           title="Eliminar equipo">
                                                            <i class="fas fa-trash"></i>
                                                            Borrar
                                                        </a>
                                                    <?php } ?>

                                                </div>

                                            </td>

                                        </tr>

                                        <!--
                                            Los siguientes datos ya no se muestran como
                                            columnas en la tabla principal para evitar que
                                            la pantalla se vea saturada.

                                            Se conservan en el sistema y se consultan
                                            mediante "Ver".
                                        -->

                                    <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<style>

    /* Apariencia general de la tabla */
    #example1 {
        font-size: 14px;
        border-radius: 6px;
        overflow: hidden;
    }

    #example1 thead th {
        background-color: #f4f6f9;
        color: #343a40;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    #example1 tbody td {
        vertical-align: middle;
    }

    #example1 tbody tr {
        transition: background-color .15s ease;
    }

    #example1 tbody tr:hover {
        background-color: #f8fbff;
    }

    /* Código del equipo */
    .codigo-equipo {
        display: inline-block;
        background: #eef5ff;
        color: #0056b3;
        border: 1px solid #cfe2ff;
        border-radius: 5px;
        padding: 4px 8px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Texto largo */
    .dato-tabla {
        display: block;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Estados */
    .estado-badge {
        font-size: 12px;
        padding: 6px 9px;
        font-weight: 600;
    }

    /* Botones */
    .acciones {
        white-space: nowrap;
    }

    .acciones .btn {
        margin: 0 1px;
        border-radius: 4px !important;
    }

    /* Caja de búsqueda de DataTables */
    #example1_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 7px 10px;
        margin-left: 6px;
        outline: none;
    }

    #example1_wrapper .dataTables_filter input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 .15rem rgba(0,123,255,.12);
    }

    /* Selector Mostrar */
    #example1_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 5px 25px 5px 8px;
        margin: 0 5px;
    }

    /* Botones superiores de reportes */
    #example1_wrapper .dt-buttons .btn {
        border-radius: 4px;
    }

    /* En pantallas pequeñas */
    @media (max-width: 767px) {

        #example1 {
            font-size: 13px;
        }

        #example1_wrapper .dataTables_filter {
            text-align: left;
            margin-top: 10px;
        }

        #example1_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
            margin-top: 5px;
        }

        .acciones .btn {
            padding: 5px 7px;
        }

        .acciones .btn i {
            margin-right: 0 !important;
        }
    }

</style>

<script>
    $(function() {

        $("#example1").DataTable({

            "pageLength": 5,

            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Equipos",
                "infoEmpty": "Mostrando 0 a 0 de 0 Equipos",
                "infoFiltered": "(Filtrado de _MAX_ total Equipos)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Equipos",
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

            /*
             * Se desactiva Responsive porque estaba provocando
             * que las columnas que no entraban aparecieran debajo
             * de la fila, haciendo que la tabla se vea desordenada.
             *
             * Ahora solamente mostramos las columnas importantes
             * en el listado y el resto se consulta desde "Ver".
             */
            "responsive": false,

            "lengthChange": true,
            "autoWidth": false,

            "scrollX": true,

            buttons: [

                {
                    extend: 'collection',
                    text: '<i class="fas fa-file-alt mr-1"></i> Reportes',
                    className: 'btn btn-secondary',

                    buttons: [
                        {
                            text: 'Copiar',
                            extend: 'copy'
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape'
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
                    text: '<i class="fas fa-columns mr-1"></i> Visor de columnas',
                    collectionLayout: 'fixed three-column'
                }

            ]

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>