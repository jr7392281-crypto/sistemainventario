<?php

include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/impresoras/listado_impresoras.php');
include('../app/controllers/areas/listado_de_areas.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de una nueva impresora</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/impresoras/create.php" method="post">

                                        <!-- Primera fila -->
                                        <div class="row">
                                            <!-- Código -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="codigo_visible">Código:</label>
                                                    <?php
                                                    $contador_de_id_impresoras = 1;

                                                    foreach ($impresoras_datos as $impresora_dato) {
                                                        $contador_de_id_impresoras = $contador_de_id_impresoras + 1;
                                                    }

                                                    $codigo_impresora = 'Muni-imp' . str_pad(
                                                        $contador_de_id_impresoras,
                                                        3,
                                                        '0',
                                                        STR_PAD_LEFT
                                                    );                                        
                                                    ?>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $codigo_impresora; ?>" disabled>

                                                    <input type="text" name="codigo"
                                                        value="<?php echo $codigo_impresora; ?>" hidden>
                                                </div>
                                            </div>

                                            <!-- Fabricante -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fabricante">Fabricante:</label>
                                                    <input type="text" id="fabricante" name="fabricante"
                                                        class="form-control" maxlength="100" required>
                                                </div>
                                            </div>

                                            <!-- Modelo -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="modelo">Modelo:</label>
                                                    <input type="text" id="modelo" name="modelo" class="form-control"
                                                        maxlength="100" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.row -->

                                        <!-- Segunda fila -->
                                        <div class="row">
                                            <!-- Tipo de equipo -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tipo_equipo">Tipo de equipo:</label>
                                                    <input type="text" id="tipo_equipo" name="tipo_equipo"
                                                        class="form-control" maxlength="100"
                                                        placeholder="Multifuncional, impresora..." required>
                                                </div>
                                            </div>

                                            <!-- Tipo de impresión -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tipo_impresion">Tipo de impresión:</label>
                                                    <input type="text" id="tipo_impresion" name="tipo_impresion"
                                                        class="form-control" maxlength="100"
                                                        placeholder="Tinta, láser monocromático..." required>
                                                </div>
                                            </div>

                                            <!-- Área -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="id_area">Área:</label>
                                                    <div style="display: flex;">
                                                        <select id="id_area" name="id_area" class="form-control"
                                                            required>
                                                            <?php

                                                            foreach ($areas_datos as $areas_dato) {
                                                                ?>
                                                                <option value="<?php echo $areas_dato['id_area']; ?>">

                                                                    <?php echo $areas_dato['nombre']; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <a href="<?php echo $URL; ?>/areas" class="btn btn-primary">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Estado -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="estado">Estado:</label>
                                                    <select id="estado" name="estado" class="form-control" required>
                                                        <option value="Operativa">Operativa</option>
                                                        <option value="En reparación">En reparación</option>
                                                        <option value="Dañada">Dañada</option>
                                                        <option value="Baja">Baja</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.row -->

                                        <!-- Observación -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="observacion">Observación:</label>
                                                    <textarea id="observacion" name="observacion" class="form-control"
                                                        rows="3"
                                                        placeholder="Ingrese alguna observación de la impresora"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.row -->

                                        <hr>

                                        <!-- Botones -->
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Guardar impresora
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-md-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>