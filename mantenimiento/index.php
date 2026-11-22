<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/notificaciones.php');
include('../app/controllers/mantenimiento/listado_mantenimientos.php');
include('../layout/parte1.php');

// Identificar el rol del usuario.
$rol_actual = strtolower(trim($rol_sesion ?? ''));

$es_soporte = ($rol_actual === 'soporte tecnico');
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

                <!-- ENCABEZADO -->
                <div class="card-header">
                    <h3 class="card-title">Mantenimientos registrados</h3>

                    <div class="card-tools">
                        <a href="create.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            Registrar mantenimiento
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
                                <th>Estado</th>
                                <th>Solicitante</th>
                                <th>Observación</th>
                                <th style="min-width: 160px;">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $contador = 0;

                            foreach ($mantenimientos_datos as $mantenimiento) {

                                $id_mantenimiento = (int) $mantenimiento['id_mantenimiento'];

                                // Mostrar el equipo o la impresora.
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

                                // Obtener estado.
                                $estado = strtolower(
                                    trim($mantenimiento['estado'] ?? 'reportado')
                                );

                                // Obtener etiqueta del estado.
                                $etiqueta_estado = estadoMantenimientoEtiqueta($estado);

                                // Colores de los estados.
                                $colores_estado = [
                                    'reportado' => 'warning',
                                    'enviado' => 'info',
                                    'aceptado' => 'primary',
                                    'rechazado' => 'danger',
                                    'resuelto' => 'success'
                                ];

                                $color_estado = $colores_estado[$estado] ?? 'secondary';
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

                                        if ($tipo === 'Preventivo') {
                                            $color = 'success';
                                        } elseif ($tipo === 'Correctivo') {
                                            $color = 'danger';
                                        } elseif ($tipo === 'Predictivo') {
                                            $color = 'info';
                                        }
                                        ?>

                                        <span class="badge badge-<?php echo $color; ?>">
                                            <?php echo htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?php echo $color_estado; ?>">
                                            <?php echo htmlspecialchars($etiqueta_estado, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($mantenimiento['usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($mantenimiento['observacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <!-- ACCIONES ORGANIZADAS VERTICALMENTE -->
                                    <td>

                                        <div style="display: flex; flex-direction: column; gap: 5px; min-width: 145px;">

                                            <!-- VER DETALLES -->
                                            <a href="show.php?id=<?php echo $id_mantenimiento; ?>"
                                               class="btn btn-info btn-sm btn-block">

                                                <i class="fas fa-eye"></i>
                                                Ver detalles

                                            </a>

                                            <!-- EDITAR Y ELIMINAR: ADMINISTRADOR -->
                                            <?php if ($rol_actual === 'administrador') { ?>

                                                <a href="update.php?id=<?php echo $id_mantenimiento; ?>"
                                                   class="btn btn-success btn-sm btn-block">

                                                    <i class="fas fa-pencil-alt"></i>
                                                    Editar

                                                </a>

                                                <a href="delete.php?id=<?php echo $id_mantenimiento; ?>"
                                                   class="btn btn-danger btn-sm btn-block">

                                                    <i class="fas fa-trash-alt"></i>
                                                    Eliminar

                                                </a>

                                            <?php } ?>

                                            <!-- FINALIZAR: SOLO SOPORTE TÉCNICO -->
                                            <?php if ($es_soporte && $estado === 'aceptado') { ?>

                                                <form action="../app/controllers/mantenimiento/finalizar.php"
                                                      method="POST"
                                                      style="margin: 0;"
                                                      onsubmit="return confirm('¿Confirmas que el mantenimiento ya fue finalizado? Se notificará al personal y a los administradores.');">

                                                    <input type="hidden"
                                                           name="id_mantenimiento"
                                                           value="<?php echo $id_mantenimiento; ?>">

                                                    <button type="submit"
                                                            class="btn btn-primary btn-sm btn-block">

                                                        <i class="fas fa-check-double"></i>
                                                        Mantenimiento finalizado

                                                    </button>

                                                </form>

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
