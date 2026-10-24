<?php include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/equipos/show_equipos.php'); 
?>

<div class="content-wrapper"> 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Detalles del Equipo</h1>
                </div>
            </div>
        </div>
    </div> <!-- /.content-header --> <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"> Datos del equipo </h3>
                            <div class="card-tools"> <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button> </div>
                        </div>
                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12"> <!-- Primera fila -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Código: </label> <input type="text" class="form-control" value="<?php echo $codigo; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Nombre del dispositivo: </label> <input type="text" class="form-control" value="<?php echo $nombre_dispositivo; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Área: </label> <input type="text" class="form-control" value="<?php echo $nombre_area; ?>" disabled> </div>
                                        </div>
                                    </div> <!-- Segunda fila -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Procesador: </label> <input type="text" class="form-control" value="<?php echo $procesador; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Memoria RAM: </label> <input type="text" class="form-control" value="<?php echo $ram; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Tarjeta gráfica: </label> <input type="text" class="form-control" value="<?php echo $tarjeta_grafica; ?>" disabled> </div>
                                        </div>
                                    </div> <!-- Tercera fila -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Sistema operativo: </label> <input type="text" class="form-control" value="<?php echo $sistema_operativo; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Almacenamiento: </label> <input type="text" class="form-control" value="<?php echo $almacenamiento; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Monitor: </label> <input type="text" class="form-control" value="<?php echo $monitor; ?>" disabled> </div>
                                        </div>
                                    </div> <!-- Cuarta fila -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Dirección IP: </label> <input type="text" class="form-control" value="<?php echo $ip; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Mouse: </label> <input type="text" class="form-control" value="<?php echo $mouse; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> <label for=""> Teclado: </label> <input type="text" class="form-control" value="<?php echo $teclado; ?>" disabled> </div>
                                        </div>
                                    </div> <!-- Quinta fila -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for=""> Estado: </label> <input type="text" class="form-control" value="<?php echo $estado; ?>" disabled> </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for=""> Fecha de registro: </label> <input type="text" class="form-control" value="<?php echo $fecha_registro; ?>" disabled> </div>
                                        </div>
                                    </div> <!-- Observación -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group"> <label for=""> Observación: </label> <textarea class="form-control" rows="4" disabled><?php echo $observacion; ?></textarea> </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group"> <a href="index.php" class="btn btn-secondary"> Cancelar </a> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper --> <?php include('../layout/parte2.php'); ?>