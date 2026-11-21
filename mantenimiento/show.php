<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/notificaciones.php');
include('../app/controllers/mantenimiento/show_mantenimiento.php');
include('../layout/parte1.php');
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
                                <input type="date" class="form-control"
                                       value="<?php echo htmlspecialchars($fecha ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de mantenimiento</label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars($tipo_mantenimiento ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado del reporte</label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars(estadoMantenimientoEtiqueta($estado_mantenimiento ?? 'reportado'), ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipo</label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars(trim(($codigo_equipo ?? '') . ' - ' . ($nombre_dispositivo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="Sin equipo asociado"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Impresora</label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars(trim(($codigo_impresora ?? '') . ' - ' . ($fabricante ?? '') . ' ' . ($modelo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="Sin impresora asociada"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Solicitante</label>
                                <input type="text" class="form-control"
                                       value="<?php echo htmlspecialchars($usuario ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea class="form-control" rows="4" readonly><?php echo htmlspecialchars($observacion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                    </div>

                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    <?php if ($rol_sesion === 'administrador') { ?>
                        <a href="update.php?id=<?php echo $id_mantenimiento_get; ?>"
                           class="btn btn-success">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </a>
                    <?php } ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>
