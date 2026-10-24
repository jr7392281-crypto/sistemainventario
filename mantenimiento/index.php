<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/controllers/mantenimiento/listado_mantenimientos.php');
include('../layout/parte1.php');
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <h1>Historial de mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">Mantenimientos registrados</h3>

                    <div class="card-tools">
                        <a href="create.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Registrar mantenimiento
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive">

                    <table id="example1" class="table table-bordered table-striped table-sm">

                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Fecha</th>
                                <th>Activo relacionado</th>
                                <th>Tipo de mantenimiento</th>
                                <th>Solicitante</th>
                                <th>Observación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $contador = 0;

                            foreach ($mantenimientos_datos as $mantenimiento) {
                                $id_mantenimiento = (int) $mantenimiento['id_mantenimiento'];

                                // Mostrar el equipo o la impresora
                                $activos = [];

                                if (!empty($mantenimiento['codigo_equipo'])) {
                                    $activos[] = $mantenimiento['codigo_equipo'] . ' - ' .
                                        $mantenimiento['nombre_dispositivo'];
                                }

                                if (!empty($mantenimiento['codigo_impresora'])) {
                                    $activos[] = $mantenimiento['codigo_impresora'] . ' - ' .
                                        $mantenimiento['fabricante'] . ' ' .
                                        $mantenimiento['modelo'];
                                }
                                ?>

                                <tr>
                                    <td class="text-center">
                                        <?php echo ++$contador; ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($mantenimiento['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php
                                        if (empty($activos)) {
                                            echo '-';
                                        } else {
                                            foreach ($activos as $activo) {
                                                echo '<div>' .
                                                    htmlspecialchars($activo, ENT_QUOTES, 'UTF-8') .
                                                    '</div>';
                                            }
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $tipo = $mantenimiento['tipo_mantenimiento'] ?? '';

                                        $color = 'secondary';

                                        if ($tipo == 'Preventivo') {
                                            $color = 'success';
                                        } elseif ($tipo == 'Correctivo') {
                                            $color = 'danger';
                                        } elseif ($tipo == 'Predictivo') {
                                            $color = 'info';
                                        }
                                        ?>

                                        <span class="badge badge-<?php echo $color; ?>">
                                            <?php echo htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>


                                    <td>
                                        <?php echo htmlspecialchars($mantenimiento['usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($mantenimiento['observacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <a href="show.php?id=<?php echo $id_mantenimiento; ?>" class="btn btn-info btn-sm"
                                                title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                                Ver
                                            </a>
                                            <?php if ($rol_sesion === 'administrador') { ?>
                                                <a href="update.php?id=<?php echo $id_mantenimiento; ?>"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa-pencil-alt"></i> Editar
                                                </a>
                                                <a href="delete.php?id=<?php echo $id_mantenimiento; ?>"
                                                    class="btn btn-danger btn-sm">
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

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<script>
    $(function () {
        $("#example1").DataTable({
            pageLength: 5,
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "No hay mantenimientos registrados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ mantenimientos",
                infoEmpty: "Mostrando 0 a 0 de 0 mantenimientos",
                infoFiltered: "(Filtrado de _MAX_ mantenimientos)",
                lengthMenu: "Mostrar _MENU_ mantenimientos",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscador:",
                zeroRecords: "Sin resultados encontrados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    });
</script>