<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_inventario.php');
include('../layout/parte1.php');
include('../app/controllers/equipos/listado_de_equipos.php');
include('../app/controllers/areas/listado_de_areas.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-12">

                    <h1 class="m-0">Registro de un nuevo equipo</h1>

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

                                <button type="button"
                                        class="btn btn-tool"
                                        data-card-widget="collapse">

                                    <i class="fas fa-minus"></i>

                                </button>

                            </div>

                        </div>

                        <!-- /.card-header -->


                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12">

                                    <form action="../app/controllers/equipos/create.php"
                                          method="post">

                                        <!-- Primera fila -->
                                        <div class="row">

                                            <!-- Código -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Código:</label>

                                                    <?php

                                                    $contador_de_id_equipos = 1;

                                                    foreach ($equipos_datos as $equipos_dato) {

                                                        $contador_de_id_equipos =
                                                            $contador_de_id_equipos + 1;

                                                    }

                                                    $codigo_equipo =
                                                        "Muni-Pc" .
                                                        str_pad(
                                                            $contador_de_id_equipos,
                                                            3,
                                                            "0",
                                                            STR_PAD_LEFT
                                                        );

                                                    ?>

                                                    <input type="text"
                                                           class="form-control"
                                                           value="<?php echo $codigo_equipo; ?>"
                                                           disabled>

                                                    <input type="text"
                                                           name="codigo"
                                                           value="<?php echo $codigo_equipo; ?>"
                                                           hidden>

                                                </div>

                                            </div>


                                            <!-- Nombre del dispositivo -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Nombre del dispositivo:</label>

                                                    <input type="text"
                                                           name="nombre_dispositivo"
                                                           class="form-control"
                                                           required>

                                                </div>

                                            </div>


                                            <!-- Procesador -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Procesador:</label>

                                                    <input type="text"
                                                           name="procesador"
                                                           class="form-control">

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.row -->


                                        <!-- Segunda fila -->
                                        <div class="row">

                                            <!-- RAM -->
                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="">RAM:</label>

                                                    <input type="text"
                                                           name="ram"
                                                           class="form-control"
                                                           placeholder="Ej: 8 GB">

                                                </div>

                                            </div>


                                            <!-- Tarjeta gráfica -->
                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="">Tarjeta gráfica:</label>

                                                    <input type="text"
                                                           name="tarjeta_grafica"
                                                           class="form-control"
                                                           placeholder="Ej: NVIDIA GTX 1650">

                                                </div>

                                            </div>


                                            <!-- Sistema operativo -->
                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="">Sistema operativo:</label>

                                                    <input type="text"
                                                           name="sistema_operativo"
                                                           class="form-control"
                                                           placeholder="Ej: Windows 11">

                                                </div>

                                            </div>


                                            <!-- Almacenamiento -->
                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="">Almacenamiento:</label>

                                                    <input type="text"
                                                           name="almacenamiento"
                                                           class="form-control"
                                                           placeholder="Ej: 512 GB SSD">

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.row -->


                                        <!-- Tercera fila -->
                                        <div class="row">

                                            <!-- Monitor -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Monitor:</label>

                                                    <input type="text"
                                                           name="monitor"
                                                           class="form-control"
                                                           placeholder="Ej: 24 pulgadas">

                                                </div>

                                            </div>


                                            <!-- Dirección IP -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Dirección IP:</label>

                                                    <input type="text"
                                                           name="ip"
                                                           class="form-control"
                                                           placeholder="Ej: 192.168.1.100">

                                                </div>

                                            </div>


                                            <!-- Mouse -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Mouse:</label>

                                                    <input type="text"
                                                           name="mouse"
                                                           class="form-control"
                                                           placeholder="Ej: Logitech">

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.row -->


                                        <!-- Cuarta fila -->
                                        <div class="row">

                                            <!-- Teclado -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Teclado:</label>

                                                    <input type="text"
                                                           name="teclado"
                                                           class="form-control"
                                                           placeholder="Ej: Logitech">

                                                </div>

                                            </div>


                                            <!-- Área -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Área:</label>

                                                    <div style="display: flex;">

                                                        <select name="id_area"
                                                                class="form-control"
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

                                                        <a href="<?php echo $URL; ?>/areas"
                                                           class="btn btn-primary">

                                                            <i class="fa fa-plus"></i>

                                                        </a>

                                                    </div>

                                                </div>

                                            </div>


                                            <!-- Estado -->
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="">Estado:</label>

                                                    <select name="estado"
                                                            class="form-control"
                                                            required>

                                                        <option value="Operativo">
                                                            Operativo
                                                        </option>

                                                        <option value="Mantenimiento">
                                                            Mantenimiento
                                                        </option>

                                                        <option value="Inactivo">
                                                            Inactivo
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.row -->


                                        <!-- Observación -->
                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="form-group">

                                                    <label for="">Observación:</label>

                                                    <textarea name="observacion"
                                                              class="form-control"
                                                              rows="3"
                                                              placeholder="Ingrese alguna observación del equipo"></textarea>

                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.row -->


                                        <hr>


                                        <!-- Botones -->
                                        <div class="form-group">

                                            <a href="index.php"
                                               class="btn btn-secondary">

                                                Cancelar

                                            </a>


                                            <button type="submit"
                                                    class="btn btn-primary">

                                                Guardar equipo

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


<?php include('../layout/parte2.php'); ?>

<?php include('../layout/mensajes.php'); ?>
