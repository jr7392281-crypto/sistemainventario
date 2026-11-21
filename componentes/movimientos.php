<?php
include('../app/config.php');
include('../layout/sesion.php');

$componentesConsulta = $pdo->query(
    'SELECT id_componente, codigo, nombre, stock_actual
     FROM tb_componentes
     WHERE estado = 1
     ORDER BY nombre ASC'
);
$componentes = $componentesConsulta->fetchAll(PDO::FETCH_ASSOC);

$mantenimientosConsulta = $pdo->query(
    'SELECT id_mantenimiento, fecha, tipo_mantenimiento
     FROM tb_mantenimientos
     ORDER BY id_mantenimiento DESC
     LIMIT 100'
);
$mantenimientos = $mantenimientosConsulta->fetchAll(PDO::FETCH_ASSOC);

$movimientosConsulta = $pdo->query(
    'SELECT m.id_movimiento, m.tipo, m.cantidad, m.observacion, m.fecha_registro,
            c.codigo, c.nombre AS componente,
            u.nombres AS usuario, m.id_mantenimiento
     FROM tb_movimientos_stock AS m
     INNER JOIN tb_componentes AS c ON c.id_componente = m.id_componente
     INNER JOIN tb_usuarios AS u ON u.id_usuario = m.id_usuario
     ORDER BY m.fecha_registro DESC, m.id_movimiento DESC
     LIMIT 100'
);
$movimientos = $movimientosConsulta->fetchAll(PDO::FETCH_ASSOC);

include('../layout/parte1.php');
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
            <?php if (!empty($componentes) && !empty($mantenimientos)) { ?>
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Registrar componente usado en un mantenimiento</h3></div>
                    <form action="../app/controllers/componentes/movimiento.php" method="post">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="id_componente">Componente</label>
                                    <select id="id_componente" name="id_componente" class="form-control" required>
                                        <option value="">Selecciona un componente</option>
                                        <?php foreach ($componentes as $componente) { ?>
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
                                        <?php foreach ($mantenimientos as $mantenimiento) { ?>
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
            <?php } elseif (empty($componentes)) { ?>
                <div class="alert alert-info">Primero registra un componente para poder anotar su uso.</div>
            <?php } elseif (empty($mantenimientos)) { ?>
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
                            <?php if (empty($movimientos)) { ?>
                                <tr><td colspan="6" class="text-center text-muted">Todavía no se han registrado componentes usados.</td></tr>
                            <?php } else { ?>
                                <?php foreach ($movimientos as $movimiento) { ?>
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
