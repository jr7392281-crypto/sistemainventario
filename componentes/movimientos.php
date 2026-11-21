<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/componentes/listado_movimientos.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0">Uso de componentes</h1>
            <a href="index.php" class="btn btn-secondary btn-sm">Volver a componentes</a>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <?php if (!empty($componentes_datos) && !empty($mantenimientos_datos)) { ?>
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Registrar componente usado en un mantenimiento</h3></div>
                    <form action="../app/controllers/componentes/movimiento.php" method="post">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="id_componente">Componente</label>
                                    <select id="id_componente" name="id_componente" class="form-control" required>
                                        <option value="">Selecciona un componente</option>
                                        <?php foreach ($componentes_datos as $componente) { ?>
                                            <option value="<?php echo (int) $componente['id_componente']; ?>">
                                                <?php echo htmlspecialchars($componente['codigo'] . ' - ' . $componente['nombre'] . ' (stock: ' . $componente['stock_actual'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="cantidad">Cantidad</label>
                                    <input id="cantidad" name="cantidad" type="number" min="1" step="1" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="id_mantenimiento">Mantenimiento atendido</label>
                                    <select id="id_mantenimiento" name="id_mantenimiento" class="form-control" required>
                                        <option value="">Selecciona un mantenimiento</option>
                                        <?php foreach ($mantenimientos_datos as $mantenimiento) { ?>
                                            <option value="<?php echo (int) $mantenimiento['id_mantenimiento']; ?>">
                                                <?php echo htmlspecialchars('#' . $mantenimiento['id_mantenimiento'] . ' - ' . $mantenimiento['fecha'] . ' - ' . $mantenimiento['tipo_mantenimiento'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="observacion">Observación</label>
                                    <input id="observacion" name="observacion" class="form-control" maxlength="255"
                                        placeholder="Ej.: memoria instalada, fuente reemplazada">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Registrar uso</button>
                        </div>
                    </form>
                </div>
            <?php } elseif (empty($componentes_datos)) { ?>
                <div class="alert alert-info">Primero registra un componente para poder anotar su uso.</div>
            <?php } elseif (empty($mantenimientos_datos)) { ?>
                <div class="alert alert-info">No hay mantenimientos registrados para asociar el uso de un componente.</div>
            <?php } ?>

            <div class="card card-outline card-secondary">
                <div class="card-header"><h3 class="card-title">Historial de componentes usados</h3></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th><th>Componente</th><th>Cantidad usada</th>
                                <th>Usuario</th><th>Mantenimiento</th><th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($movimientos_datos)) { ?>
                                <tr><td colspan="6" class="text-center text-muted">Todavía no se han registrado componentes usados.</td></tr>
                            <?php } else { ?>
                                <?php foreach ($movimientos_datos as $movimiento) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($movimiento['fecha_registro'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($movimiento['codigo'] . ' - ' . $movimiento['componente'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo (int) $movimiento['cantidad']; ?></td>
                                        <td><?php echo htmlspecialchars($movimiento['usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo $movimiento['id_mantenimiento'] ? '#' . (int) $movimiento['id_mantenimiento'] : '—'; ?></td>
                                        <td><?php echo htmlspecialchars($movimiento['observacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                                <?php } ?>
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
