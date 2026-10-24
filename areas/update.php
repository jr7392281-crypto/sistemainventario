<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/solo_administrador.php');
include('../app/controllers/areas/update_area.php');
include('../layout/parte1.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Edición del Área</h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-5">
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Llene los datos con cuidado</h3>
            </div>

            <div class="card-body">
              <form action="../app/controllers/areas/update.php" method="post">
                <div class="form-group">
                  <input type="hidden" name="id_area" value="<?php echo  $id_area_get; ?>">
                  <label>Nombre del Área</label>
                  <input type="text" name="nombre" class="form-control" placeholder="Escriba el Área..."
                    value="<?php echo $nombre_area; ?>" required>
                </div>
                <hr>
                <div class="form-group">
                  <a href="index.php" class="btn btn-secondary">Cancelar</a>
                  <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>