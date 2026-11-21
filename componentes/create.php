<?php
include('../app/config.php');
include('../layout/sesion.php');
$consulta_componentes = $pdo->query('SELECT id_componente FROM tb_componentes');
$componentes_datos = $consulta_componentes->fetchAll(PDO::FETCH_ASSOC);
include('../layout/parte1.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Registrar componente</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos del componente</h3>
                </div>
                <form action="../app/controllers/componentes/create.php" method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="codigo_interno">Código</label>
                                <?php
                                $contador_de_id_componentes = 1;
                                foreach ($componentes_datos as $componente_dato) {
                                    $contador_de_id_componentes++;
                                }
                                $codigo_componente = 'Comp' . str_pad(
                                    $contador_de_id_componentes,
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                );
                                ?>
                                <input id="codigo_interno" class="form-control"
                                    value="<?php echo htmlspecialchars($codigo_componente, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                <input type="hidden" name="codigo"
                                    value="<?php echo htmlspecialchars($codigo_componente, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="nombre">Nombre del componente</label>
                                <input id="nombre" name="nombre" class="form-control" maxlength="120" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tipo_componente">Categoría</label>
                                <select id="tipo_componente" name="tipo_componente" class="form-control" required>
                                    <option value="">Selecciona una categoría</option>
                                    <option value="Repuesto">Repuesto</option>
                                    <option value="Consumible">Consumible</option>
                                    <option value="Accesorio">Accesorio</option>
                                    <option value="Insumo de mantenimiento">Insumo de mantenimiento</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="marca">Marca</label>
                                <input id="marca" name="marca" class="form-control" maxlength="80">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="aplica_a">Aplica a</label>
                                <select id="aplica_a" name="aplica_a" class="form-control" required>
                                    <option value="" selected disabled>Selecciona el activo</option>
                                    <option value="Equipo">Computadoras y equipos</option>
                                    <option value="Impresora">Impresoras</option>
                                    <option value="Ambos">Ambos</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="especificacion">Especificación</label>
                                <textarea id="especificacion" name="especificacion" class="form-control" rows="2"
                                    maxlength="100" placeholder="Ej.: DDR4 3200 MHz, SODIMM para laptop"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="stock_minimo">Stock mínimo</label>
                                <input id="stock_minimo" name="stock_minimo" type="number" min="0" step="1"
                                    class="form-control" value="0" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="stock_actual">Cantidad disponible</label>
                                <input id="stock_actual" name="stock_actual" type="number" min="0" step="1"
                                    class="form-control" value="0" required>
                            </div>
                        </div>
                        <small class="text-muted">El código se asigna automáticamente. Al usar el componente en un
                            mantenimiento, se descontará la cantidad disponible.</small>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar
                            componente</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>
