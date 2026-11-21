<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../app/controllers/mantenimiento/update_mantenimiento.php');
include('../layout/parte1.php');

$activo = '';
if (!empty($mantenimiento['codigo_equipo'])) {
    $activo = 'Equipo: ' . $mantenimiento['codigo_equipo'] . ' - ' . $mantenimiento['nombre_dispositivo'];
} elseif (!empty($mantenimiento['codigo_impresora'])) {
    $activo = 'Impresora: ' . $mantenimiento['codigo_impresora'] . ' - ' .
        $mantenimiento['fabricante'] . ' ' . $mantenimiento['modelo'];
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Editar mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Modifique los datos del mantenimiento</h3>
                        </div>

                        <div class="card-body">
                            <form action="../app/controllers/mantenimiento/update.php" method="post">
                                <input type="hidden" name="id_mantenimiento"
                                    value="<?php echo (int) $mantenimiento['id_mantenimiento']; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Activo relacionado:</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo htmlspecialchars($activo, ENT_QUOTES, 'UTF-8'); ?>"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Solicitante:</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo htmlspecialchars($mantenimiento['usuario'], ENT_QUOTES, 'UTF-8'); ?>"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fecha:</label>
                                            <input type="date" name="fecha" class="form-control"
                                                value="<?php echo htmlspecialchars($mantenimiento['fecha'], ENT_QUOTES, 'UTF-8'); ?>"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tipo de mantenimiento:</label>
                                            <select name="tipo_mantenimiento" class="form-control" required>
                                                <?php
                                                $tipos = ['Preventivo', 'Correctivo', 'Predictivo', 'Otro'];
                                                foreach ($tipos as $tipo) {
                                                    $seleccionado = $mantenimiento['tipo_mantenimiento'] == $tipo
                                                        ? 'selected'
                                                        : '';
                                                ?>
                                                    <option value="<?php echo $tipo; ?>" <?php echo $seleccionado; ?>>
                                                        <?php echo $tipo; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <?php if ($estado_disponible) { ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Estado del reporte:</label>
                                                <select name="estado" class="form-control" required>
                                                    <?php
                                                    $estados = [
                                                        'reportado' => 'Reportado',
                                                        'en_proceso' => 'En proceso',
                                                        'resuelto' => 'Resuelto',
                                                    ];
                                                    foreach ($estados as $valor_estado => $etiqueta_estado) {
                                                        $seleccionado = ($mantenimiento['estado'] ?? 'reportado') === $valor_estado
                                                            ? 'selected'
                                                            : '';
                                                    ?>
                                                        <option value="<?php echo htmlspecialchars($valor_estado, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $seleccionado; ?>>
                                                            <?php echo htmlspecialchars($etiqueta_estado, ENT_QUOTES, 'UTF-8'); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-12">
                                            <p class="text-muted">Aplica la migración de notificaciones para habilitar el seguimiento por estados.</p>
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Observación:</label>
                                            <textarea name="observacion" class="form-control" rows="4"
                                                required><?php echo htmlspecialchars($mantenimiento['observacion'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
