<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/controllers/equipos/listado_de_equipos.php');
include('../app/controllers/impresoras/listado_impresoras.php');
include('../layout/parte1.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Registrar mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Datos del mantenimiento</h3>
                        </div>

                        <form action="../app/controllers/mantenimiento/create.php" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_equipo">Equipo (selecciona equipo o impresora)</label>
                                            <select name="id_equipo" id="id_equipo" class="form-control">
                                                <option value="">Sin equipo</option>
                                                <?php foreach ($equipos_datos as $equipo) { ?>
                                                    <option value="<?php echo (int) $equipo['id_equipo']; ?>">
                                                        <?php echo htmlspecialchars($equipo['codigo'] . ' - ' . $equipo['nombre_dispositivo'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_impresora">Impresora</label>
                                            <select name="id_impresora" id="id_impresora" class="form-control">
                                                <option value="">Sin impresora</option>
                                                <?php foreach ($impresoras_datos as $impresora) { ?>
                                                    <option value="<?php echo (int) $impresora['id_impresora']; ?>">
                                                        <?php echo htmlspecialchars($impresora['codigo'] . ' - ' . $impresora['fabricante'] . ' ' . $impresora['modelo'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fecha">Fecha</label>
                                            <input type="date" name="fecha" id="fecha" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tipo_mantenimiento">Tipo de mantenimiento</label>
                                            <select name="tipo_mantenimiento" id="tipo_mantenimiento"
                                                class="form-control" required>
                                                <option value="">Seleccione un tipo</option>
                                                <option value="Preventivo">Preventivo</option>
                                                <option value="Correctivo">Correctivo</option>
                                                <option value="Predictivo">Predictivo</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="observacion">Observación</label>
                                            <textarea name="observacion" id="observacion" class="form-control"
                                                rows="4" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
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

<script>
    $('#id_equipo').on('change', function () {
        if (this.value !== '') {
            $('#id_impresora').val('');
        }
    });

    $('#id_impresora').on('change', function () {
        if (this.value !== '') {
            $('#id_equipo').val('');
        }
    });
</script>
