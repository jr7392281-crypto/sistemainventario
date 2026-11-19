<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_inventario.php');
include('../layout/parte1.php');
include('../app/controllers/areas/listado_de_areas.php');
include('../app/controllers/equipos/show_equipos.php');

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de un nuevo equipo</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Escriba con cuidado los datos</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="../app/controllers/equipos/update.php" method="post">
                                <input type="text" value="<?php echo $id_equipo_get; ?>" name="id_equipo" hidden>

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Código:</label>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $codigo; ?>" disabled>
                                                    <input type="text" name="codigo" value="<?php echo $codigo; ?>" hidden>
                                                </div>
                                            </div>

<div class="col-md-4"> 
    <div class="form-group"> 
        <label for="">Área:</label> 
        <div style="display: flex"> 
            <select name="id_area" id="" class="form-control" required> 
                <?php 
                $sql_areas = "SELECT * FROM tb_areas ORDER BY nombre ASC";
                $query_areas = $pdo->prepare($sql_areas);
                $query_areas->execute();
                $areas_datos = $query_areas->fetchAll(PDO::FETCH_ASSOC);

                foreach ($areas_datos as $areas_dato){ 
                    $nombre_area_tabla = $areas_dato['nombre']; 
                    $id_area_tabla = $areas_dato['id_area']; 
                ?> 
                    <option value="<?php echo $id_area_tabla; ?>"
                        <?php if($id_area_tabla == $id_area){ ?> selected="selected" <?php } ?>>
                        <?php echo $nombre_area_tabla; ?> 
                    </option> 
                <?php 
                } 
                ?> 
            </select> 
        </div> 
    </div> 
</div>






                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Nombre del dispositivo:</label>
                                                    <input type="text" name="nombre_dispositivo" value="<?php echo $nombre_dispositivo; ?>" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Procesador:</label>
                                                    <input type="text" name="procesador" value="<?php echo $procesador; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Memoria RAM:</label>
                                                    <input type="text" name="ram" value="<?php echo $ram; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Tarjeta gráfica:</label>
                                                    <input type="text" name="tarjeta_grafica" value="<?php echo $tarjeta_grafica; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Sistema operativo:</label>
                                                    <input type="text" name="sistema_operativo" value="<?php echo $sistema_operativo; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Almacenamiento:</label>
                                                    <input type="text" name="almacenamiento" value="<?php echo $almacenamiento; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Monitor:</label>
                                                    <input type="text" name="monitor" value="<?php echo $monitor; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Dirección IP:</label>
                                                    <input type="text" name="ip" value="<?php echo $ip; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Mouse:</label>
                                                    <input type="text" name="mouse" value="<?php echo $mouse; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Teclado:</label>
                                                    <input type="text" name="teclado" value="<?php echo $teclado; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Estado:</label>
                                                    <select name="estado" id="" class="form-control" required>
                                                        <option value="Operativo" <?php if ($estado == "Operativo") { ?> selected="selected" <?php } ?>>
                                                            Operativo
                                                        </option>
                                                        <option value="Mantenimiento" <?php if ($estado == "Mantenimiento") { ?> selected="selected" <?php } ?>>
                                                            Mantenimiento
                                                        </option>
                                                        <option value="Inoperativo" <?php if ($estado == "Inoperativo") { ?> selected="selected" <?php } ?>>
                                                            Inoperativo
                                                        </option>
                                                        <option value="Baja" <?php if ($estado == "Baja") { ?> selected="selected" <?php } ?>>
                                                            Baja
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Fecha de registro:</label>
                                                    <input type="text" class="form-control" value="<?php echo $fecha_registro; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Observación:</label>
                                                    <textarea name="observacion" id="" cols="30" rows="2" class="form-control"><?php echo $observacion; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>




                                <hr>
                                <div class="form-group">
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Actualizar equipo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

           
    </div>

    <!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('../layout/parte2.php'); ?>
