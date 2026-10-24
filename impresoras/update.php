<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../app/controllers/impresoras/show_impresora.php');
include('../app/controllers/areas/listado_de_areas.php');
include('../layout/parte1.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Actualizar impresora</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos de la impresora</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <form action="../app/controllers/impresoras/update.php" method="post">
                            <input type="hidden" name="id_impresora" value="<?php echo  $impresora['id_impresora']; ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="codigo">Código:</label>
                                            <input id="codigo" class="form-control" value="<?php echo htmlspecialchars($impresora['codigo'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fabricante">Fabricante:</label>
                                            <input id="fabricante" name="fabricante" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($impresora['fabricante'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="modelo">Modelo:</label>
                                            <input id="modelo" name="modelo" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($impresora['modelo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tipo_equipo">Tipo de equipo:</label>
                                            <input id="tipo_equipo" name="tipo_equipo" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($impresora['tipo_equipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tipo_impresion">Tipo de impresión:</label>
                                            <input id="tipo_impresion" name="tipo_impresion" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($impresora['tipo_impresion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_area">Área:</label>
                                            <select id="id_area" name="id_area" class="form-control" required>
                                                <?php foreach ($areas_datos as $area) {
                                                    $id_area = $area['id_area']; ?>
                                                    <option value="<?php echo $id_area; ?>" <?php if ($id_area === (int) $impresora['id_area']) { ?>selected<?php } ?>>
                                                        <?php echo htmlspecialchars($area['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado">Estado:</label>
                                            <select id="estado" name="estado" class="form-control" required>
                                                <?php foreach (['Operativa', 'En reparación', 'Dañada', 'Baja'] as $estado) { ?>
                                                    <option value="<?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($estado === $impresora['estado']) { ?>selected<?php } ?>>
                                                        <?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="observacion">Observación:</label>
                                            <textarea id="observacion" name="observacion" class="form-control" rows="4"><?php echo htmlspecialchars($impresora['observacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Actualizar impresora</button>
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
