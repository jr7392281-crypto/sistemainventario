<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador_o_super_administrador.php');
include('../app/controllers/mantenimiento/delete_mantenimiento.php');
include('../layout/parte1.php');

$activo = '-';
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
            <h1 class="m-0">Eliminar mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Datos del mantenimiento</h3>
                        </div>

                        <form action="../app/controllers/mantenimiento/delete.php" method="post">
                            <input type="hidden" name="id_mantenimiento"
                                value="<?php echo (int) $mantenimiento['id_mantenimiento']; ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Activo:</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo htmlspecialchars($activo, ENT_QUOTES, 'UTF-8'); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha:</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo htmlspecialchars($mantenimiento['fecha'], ENT_QUOTES, 'UTF-8'); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo:</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo htmlspecialchars($mantenimiento['tipo_mantenimiento'], ENT_QUOTES, 'UTF-8'); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Observación:</label>
                                            <textarea class="form-control" rows="3" disabled><?php
                                                echo htmlspecialchars($mantenimiento['observacion'], ENT_QUOTES, 'UTF-8');
                                            ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-danger mb-0">
                                    ¿Está seguro de eliminar este registro de mantenimiento?
                                </p>
                            </div>

                            <div class="card-footer">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
