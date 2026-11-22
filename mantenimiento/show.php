<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/notificaciones.php');
include('../app/controllers/mantenimiento/show_mantenimiento.php');
include('../layout/parte1.php');

// ======================================================
// IDENTIFICAR EL ROL DEL USUARIO
// ======================================================

$rol_actual = strtolower(trim($rol_sesion ?? ''));

// Permisos.
$es_administrador = in_array(
    $rol_actual,
    ['administrador', 'super administrador'],
    true
);

$es_soporte = ($rol_actual === 'soporte tecnico');

// Obtener el estado actual.
$estado_actual = strtolower(trim($estado_mantenimiento ?? 'reportado'));

// Determinar las acciones disponibles.
$puede_enviar = $es_administrador &&
    ($estado_actual === 'reportado' || $estado_actual === '');

$puede_resolver = $es_soporte &&
    $estado_actual === 'enviado';

// ======================================================
// TEXTO DEL ESTADO
// ======================================================

$etiqueta_estado = '';
$clase_estado = '';
$icono_estado = '';

switch ($estado_actual) {

    case 'reportado':
    case '':
        $etiqueta_estado = 'Pendiente de revisión';
        $clase_estado = 'text-warning';
        $icono_estado = 'fa-clock';
        break;

    case 'enviado':
        $etiqueta_estado = 'Enviado a Soporte técnico';
        $clase_estado = 'text-info';
        $icono_estado = 'fa-paper-plane';
        break;

    case 'aceptado':
        $etiqueta_estado = 'Mantenimiento aceptado';
        $clase_estado = 'text-success';
        $icono_estado = 'fa-check-circle';
        break;

    case 'rechazado':
        $etiqueta_estado = 'Mantenimiento rechazado';
        $clase_estado = 'text-danger';
        $icono_estado = 'fa-times-circle';
        break;

    default:
        $etiqueta_estado = ucfirst($estado_actual);
        $clase_estado = 'text-secondary';
        $icono_estado = 'fa-info-circle';
        break;
}
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Detalles del mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">Información del mantenimiento</h3>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($fecha ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de mantenimiento</label>
                                <input type="text"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($tipo_mantenimiento ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <!-- ESTADO DEL MANTENIMIENTO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado del reporte</label>

                                <div class="form-control d-flex align-items-center"
                                     style="height: 38px;">

                                    <span class="<?php echo $clase_estado; ?> font-weight-bold">
                                        <i class="fas <?php echo $icono_estado; ?> mr-2"></i>
                                        <?php echo htmlspecialchars($etiqueta_estado, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipo</label>
                                <input type="text"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars(trim(($codigo_equipo ?? '') . ' - ' . ($nombre_dispositivo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="Sin equipo asociado"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Impresora</label>
                                <input type="text"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars(trim(($codigo_impresora ?? '') . ' - ' . ($fabricante ?? '') . ' ' . ($modelo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="Sin impresora asociada"
                                       readonly>
                            </div>
                        </div>

                        <!-- ACCIONES DEL ADMINISTRADOR -->
                        <?php if ($puede_enviar) { ?>

                            <div class="col-md-12">
                                <div class="form-group">

                                    <label>Gestionar solicitud</label>
                                    <br>

                                    <form action="../app/controllers/mantenimiento/enviar_encargado.php"
                                          method="POST"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('¿Estás seguro de enviar esta solicitud a Soporte técnico?');">

                                        <input type="hidden"
                                               name="id_mantenimiento"
                                               value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i>
                                            Enviar a Soporte técnico
                                        </button>

                                    </form>

                                </div>
                            </div>

                        <?php } ?>

                        <!-- ACCIONES DE SOPORTE TÉCNICO -->
                        <?php if ($puede_resolver) { ?>

                            <div class="col-md-12">
                                <div class="form-group">

                                    <label>Resolver mantenimiento</label>
                                    <br>

                                    <form action="../app/controllers/mantenimiento/aceptar.php"
                                          method="POST"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('¿Estás seguro de aceptar este mantenimiento?');">

                                        <input type="hidden"
                                               name="id_mantenimiento"
                                               value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i>
                                            Aceptar mantenimiento
                                        </button>

                                    </form>

                                    <form action="../app/controllers/mantenimiento/rechazar.php"
                                          method="POST"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('¿Estás seguro de rechazar este mantenimiento?');">

                                        <input type="hidden"
                                               name="id_mantenimiento"
                                               value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times"></i>
                                            Rechazar mantenimiento
                                        </button>

                                    </form>

                                </div>
                            </div>

                        <?php } ?>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Solicitante</label>
                                <input type="text"
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($usuario ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea class="form-control"
                                          rows="4"
                                          readonly><?php echo htmlspecialchars($observacion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                    </div>

                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>

                    <?php if ($rol_actual === 'administrador') { ?>

                        <a href="update.php?id=<?php echo (int) $id_mantenimiento_get; ?>"
                           class="btn btn-success">
                            <i class="fas fa-pencil-alt"></i>
                            Editar
                        </a>

                    <?php } ?>

                </div>
            </div>

        </div>
    </div>
</div>
<?php include('../layout/parte2.php'); ?>
