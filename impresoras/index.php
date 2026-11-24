<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/impresoras/listado_impresoras.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de Impresoras</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Impresoras registradas</h3>
                            <div class="card-tools">
                                <?php if (in_array($rol_sesion, ['administrador', 'encargado de inventario'], true)) { ?>
                                    <a href="create.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Registrar impresora
                                    </a>
                                <?php } ?>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>

                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="table-responsive">
                                <table id="example1" class="table table-hover table-bordered align-middle" style="width:100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width:55px;">Nro</th>
                                            <th>Código</th>
                                            <th>Impresora</th>
                                            <th>Tipo</th>
                                            <th class="text-center">Área</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;

                                        foreach ($impresoras_datos as $impresora_dato) {
                                            $id_impresora = $impresora_dato['id_impresora'];

                                            $codigo = htmlspecialchars($impresora_dato['codigo'] ?? '', ENT_QUOTES, 'UTF-8');
                                            $fabricante = htmlspecialchars($impresora_dato['fabricante'] ?? '', ENT_QUOTES, 'UTF-8');
                                            $modelo = htmlspecialchars($impresora_dato['modelo'] ?? '', ENT_QUOTES, 'UTF-8');
                                            $area = htmlspecialchars($impresora_dato['area'] ?? '', ENT_QUOTES, 'UTF-8');
                                            $estado = htmlspecialchars($impresora_dato['estado'] ?? '', ENT_QUOTES, 'UTF-8');
                                            ?>
                                            <tr>
                                                <td class="text-center font-weight-bold">
                                                    <?php echo ++$contador; ?>
                                                </td>
                                                <td>
                                                    <span class="codigo-impresora">
                                                        <?php echo $codigo; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold"><?php echo $fabricante; ?></div>
                                                    <span class="dato-tabla"><?php echo $modelo; ?></span>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($impresora_dato['tipo_impresora'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                                    <span class="text-muted">-</span>
                                                    <?php echo htmlspecialchars($impresora_dato['tipo_impresion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-light border px-2 py-1"><?php echo $area; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($estado === 'Operativa') { ?>
                                                        <span class="badge badge-success estado-badge"><?php echo $estado; ?></span>
                                                    <?php } elseif ($estado === 'En reparación') { ?>
                                                        <span class="badge badge-warning estado-badge"><?php echo $estado; ?></span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-danger estado-badge"><?php echo $estado; ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group acciones" role="group">
                                                        <a href="show.php?id=<?php echo $id_impresora; ?>"
                                                            class="btn btn-info btn-sm" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                            Ver
                                                        </a>
                                                        <?php if (in_array($rol_sesion, ['administrador', 'encargado de inventario'], true)) { ?>
                                                            <a href="update.php?id=<?php echo $id_impresora; ?>"
                                                                class="btn btn-success btn-sm" title="Editar impresora">
                                                                <i class="fas fa-pencil-alt"></i> Editar
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($rol_sesion === 'administrador') { ?>
                                                            <a href="delete.php?id=<?php echo $id_impresora; ?>"
                                                                class="btn btn-danger btn-sm" title="Eliminar impresora">
                                                                <i class="fas fa-trash-alt"></i> Eliminar
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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


<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<style>
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

    #example1 tbody tr:hover {
        background-color: #f8fbff;
    }

    .codigo-impresora {
        display: inline-block;
        background: #eef5ff;
        color: #0056b3;
        border: 1px solid #cfe2ff;
        border-radius: 5px;
        padding: 4px 8px;
        font-weight: 600;
        white-space: nowrap;
    }

    .dato-tabla {
        display: block;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .estado-badge {
        font-size: 12px;
        padding: 6px 9px;
        font-weight: 600;
    }

    .acciones {
        white-space: nowrap;
    }
</style>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Impresoras",
                "infoEmpty": "Mostrando 0 a 0 de 0 Impresoras",
                "infoFiltered": "(Filtrado de _MAX_ total Impresoras)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Impresoras",
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
            "responsive": true, "lengthChange": true, "autoWidth": false,
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
                }
                ]
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
