<?php
 include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador_o_super_administrador.php');
include('../layout/parte1.php');
include('../app/controllers/equipos/show_equipos.php'); 
?> 
<div class="content-wrapper"> <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Datos del equipo: <?php echo $nombre_dispositivo; ?> a ser eliminado</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header --> <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">¿Esta seguro de eliminar el equipo?</h3>
                            <div class="card-tools"> <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button> </div>
                        </div>
                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/equipos/delete.php" method="post"> <input type="text" name="id_equipo" value="<?php echo $id_equipo_get; ?>" hidden>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Código:</label> <input type="text" class="form-control" value="<?php echo $codigo; ?>" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Área:</label>
                                                            <div style="display: flex"> <input type="text" class="form-control" value="<?php echo $nombre_area; ?>" disabled> </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Nombre del dispositivo:</label> <input type="text" name="nombre_dispositivo" value="<?php echo $nombre_dispositivo; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Procesador:</label> <input type="text" name="procesador" value="<?php echo $procesador; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Memoria RAM:</label> <input type="text" name="ram" value="<?php echo $ram; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Tarjeta gráfica:</label> <input type="text" name="tarjeta_grafica" value="<?php echo $tarjeta_grafica; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Sistema operativo:</label> <input type="text" name="sistema_operativo" value="<?php echo $sistema_operativo; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Almacenamiento:</label> <input type="text" name="almacenamiento" value="<?php echo $almacenamiento; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Monitor:</label> <input type="text" name="monitor" value="<?php echo $monitor; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Dirección IP:</label> <input type="text" name="ip" value="<?php echo $ip; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Mouse:</label> <input type="text" name="mouse" value="<?php echo $mouse; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group"> <label for="">Teclado:</label> <input type="text" name="teclado" value="<?php echo $teclado; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group"> <label for="">Estado:</label> <input type="text" name="estado" value="<?php echo $estado; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"> <label for="">Fecha de registro:</label> <input type="text" name="fecha_registro" value="<?php echo $fecha_registro; ?>" class="form-control" disabled> </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group"> <label for="">Observación:</label> <textarea name="observacion" id="" cols="30" rows="2" class="form-control" disabled><?php echo $observacion; ?></textarea> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group"> <a href="index.php" class="btn btn-secondary"> Cancelar </a> <button class="btn btn-danger"> <i class="fa fa-trash"></i> Borrar equipo </button> </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper --> 

<?php 
include('../layout/mensajes.php'); 
include('../layout/parte2.php'); 
?>
