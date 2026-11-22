<?php
include('../app/config.php');
include('../layout/sesion.php');

$rol_actual = strtolower(trim((string) ($rol_sesion ?? '')));
$roles_componentes = ['administrador', 'super administrador', 'encargado de inventario', 'soporte tecnico'];
if (!in_array($rol_actual, $roles_componentes, true)) {
    $_SESSION['mensaje'] = 'Tu rol no tiene acceso a los componentes.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
    exit;
}
$puede_registrar_componente = in_array($rol_actual, ['administrador', 'super administrador', 'encargado de inventario'], true);

include('../layout/parte1.php');
include('../app/controllers/componentes/listado_componentes.php');
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
                            <i class="fas fa-tools mr-1"></i>
                            <?php echo $rol_actual === 'soporte tecnico' ? 'Registrar uso en mantenimiento' : 'Historial de uso'; ?>
                        </a>
                        <?php if ($puede_registrar_componente) { ?>
                        <a href="create.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Registrar componente
                        </a>
                        <?php } ?>
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
                            <?php if (empty($componentes_datos)) { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Todavía no hay componentes registrados.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($componentes_datos as $componente) {
                                    $stockBajo = $componente['stock_actual'] <= $componente['stock_minimo'];
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
                                                <?php echo $componente['stock_actual']; ?>
                                            </span>
                                            <?php if ($stockBajo) { ?><small class="d-block text-danger">Stock bajo</small><?php } ?>
                                        </td>
                                        <td class="text-center"><?php echo $componente['stock_minimo']; ?></td>
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
