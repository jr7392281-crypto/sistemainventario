<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador_o_super_administrador.php');
include('../app/controllers/impresoras/show_impresora.php');
include('../layout/parte1.php');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Datos de la impresora: <?php echo $fabricante . ' ' . $modelo; ?> a ser eliminada</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">¿Está seguro de eliminar la impresora?</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/impresoras/delete.php" method="post">
                                        <input type="text" name="id_impresora" value="<?php echo $id_impresora_get; ?>" hidden>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Código:</label>
                                                            <input type="text" class="form-control" value="<?php echo $codigo; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Área:</label>
                                                            <input type="text" class="form-control" value="<?php echo $nombre_area; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Fabricante:</label>
                                                            <input type="text" class="form-control" value="<?php echo $fabricante; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Modelo:</label>
                                                            <input type="text" class="form-control" value="<?php echo $modelo; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Tipo de impresora:</label>
                                                            <input type="text" class="form-control" value="<?php echo $tipo_impresora; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Tipo de impresión:</label>
                                                            <input type="text" class="form-control" value="<?php echo $tipo_impresion; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Estado:</label>
                                                            <input type="text" class="form-control" value="<?php echo $estado; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha de registro:</label>
                                                            <input type="text" class="form-control" value="<?php echo $fecha_registro; ?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Observación:</label>
                                                            <textarea rows="2" class="form-control" disabled><?php echo $observacion; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> Borrar impresora
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../layout/mensajes.php');
include('../layout/parte2.php');
?>
