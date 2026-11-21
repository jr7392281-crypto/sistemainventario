<?php
include('../app/config.php');
include('../layout/sesion.php');
$consulta_componentes = $pdo->query(
    'SELECT codigo, nombre, tipo_componente, aplica_a, marca,
            especificacion, stock_actual, stock_minimo
     FROM tb_componentes
     WHERE estado = 1
     ORDER BY tipo_componente, nombre'
);
$componentes = $consulta_componentes->fetchAll(PDO::FETCH_ASSOC);
include('../layout/parte1.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0">Componentes</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Componentes disponibles</h3>
                    <div class="card-tools">
                        <a href="movimientos.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-tools mr-1"></i> Registrar uso en mantenimiento
                        </a>
                        <a href="create.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Registrar componente
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Componente</th>
                                <th>Categoría</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($componentes)) { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Todavía no hay componentes registrados.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($componentes as $componente) {
                                    $stock = (int) $componente['stock_actual'];
                                    $minimo = (int) $componente['stock_minimo'];
                                    $stockBajo = $stock <= $minimo;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($componente['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($componente['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <?php
                                            $detallesComponente = array_filter([
                                                $componente['marca'] ?? '',
                                                $componente['aplica_a'] ?? '',
                                                $componente['especificacion'] ?? '',
                                            ], static function ($detalle) {
                                                return trim((string) $detalle) !== '';
                                            });
                                            if (!empty($detallesComponente)) { ?>
                                                <small class="d-block text-muted">
                                                    <?php echo htmlspecialchars(implode(' · ', $detallesComponente), ENT_QUOTES, 'UTF-8'); ?>
                                                </small>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($componente['tipo_componente'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-<?php echo $stockBajo ? 'warning' : 'success'; ?>">
                                                <?php echo $stock; ?>
                                            </span>
                                            <?php if ($stockBajo) { ?><small class="d-block text-danger">Stock bajo</small><?php } ?>
                                        </td>
                                        <td class="text-center"><?php echo $minimo; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <p class="text-muted small mb-0">Al registrar el uso de un componente en un mantenimiento, se descuenta del stock y queda guardado en el historial.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
