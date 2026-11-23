<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../app/notificaciones.php');
include('../app/controllers/mantenimiento/show_mantenimiento.php');
include('../layout/parte1.php');

// ======================================================
// IDENTIFICAR ROL
// ======================================================

$rol_actual = strtolower(trim($rol_sesion ?? ''));

$es_administrador = in_array(
    $rol_actual,
    ['administrador', 'super administrador'],
    true
);

$es_soporte = ($rol_actual === 'soporte tecnico');

$estado_actual = strtolower(trim($estado_mantenimiento ?? 'reportado'));

$puede_enviar = $es_administrador &&
    in_array($estado_actual, ['reportado', 'rechazado', ''], true);

$puede_atender = $es_soporte && (
    $estado_actual === 'enviado' ||
    ($estado_actual === 'aceptado' && empty($tecnico_asignado))
);

$puede_resolver = $es_soporte &&
    $estado_actual === 'aceptado' &&
    (int) ($tecnico_asignado ?? 0) === (int) $id_usuario_sesion;

// ======================================================
// LISTAR TÉCNICOS Y CARGA DE TRABAJO
// ======================================================

$tecnicos = [];

if ($puede_enviar) {

    $consulta = $pdo->prepare("
        SELECT
            u.id_usuario,
            u.nombres,
            COUNT(m.id_mantenimiento) AS mantenimientos_activos
        FROM tb_usuarios u
        INNER JOIN tb_roles r
            ON r.id_rol = u.id_rol
        LEFT JOIN tb_mantenimientos m
            ON m.id_tecnico_asignado = u.id_usuario
            AND LOWER(TRIM(m.estado)) IN ('enviado', 'aceptado')
        WHERE LOWER(TRIM(r.rol)) = 'soporte tecnico'
        GROUP BY u.id_usuario, u.nombres
        ORDER BY mantenimientos_activos ASC, u.nombres ASC
    ");

    $consulta->execute();
    $tecnicos = $consulta->fetchAll(PDO::FETCH_ASSOC);
}

// ======================================================
// TEXTO DEL ESTADO
// ======================================================

$etiqueta_estado = '';
$clase_estado = '';
$icono_estado = '';

switch ($estado_actual) {

    case 'reportado':
    case '':
        $etiqueta_estado = 'Pendiente de revisión';
        $clase_estado = 'text-warning';
        $icono_estado = 'fa-clock';
        break;

    case 'enviado':
        $etiqueta_estado = 'Enviado a Soporte técnico';
        $clase_estado = 'text-info';
        $icono_estado = 'fa-paper-plane';
        break;

    case 'aceptado':
        $etiqueta_estado = 'Mantenimiento aceptado';
        $clase_estado = 'text-success';
        $icono_estado = 'fa-check-circle';
        break;

    case 'resuelto':
        $etiqueta_estado = 'Mantenimiento resuelto';
        $clase_estado = 'text-success';
        $icono_estado = 'fa-check-double';
        break;

    case 'rechazado':
        $etiqueta_estado = 'Mantenimiento rechazado';
        $clase_estado = 'text-danger';
        $icono_estado = 'fa-times-circle';
        break;

    default:
        $etiqueta_estado = ucfirst($estado_actual);
        $clase_estado = 'text-secondary';
        $icono_estado = 'fa-info-circle';
        break;
}
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Detalles del mantenimiento</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">Información del mantenimiento</h3>
                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- FECHA -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($fecha ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                            </div>
                        </div>

                        <!-- TIPO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de mantenimiento</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($tipo_mantenimiento ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                            </div>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado del reporte</label>

                                <div class="form-control d-flex align-items-center"
                                    style="height: 38px;">

                                    <span class="<?php echo $clase_estado; ?> font-weight-bold">
                                        <i class="fas <?php echo $icono_estado; ?> mr-2"></i>
                                        <?php echo htmlspecialchars($etiqueta_estado, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>

                                </div>
                            </div>
                        </div>

                        <!-- EQUIPO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipo</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(trim(($codigo_equipo ?? '') . ' - ' . ($nombre_dispositivo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="Sin equipo asociado"
                                    readonly>
                            </div>
                        </div>

                        <!-- IMPRESORA -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Impresora</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars(trim(($codigo_impresora ?? '') . ' - ' . ($fabricante ?? '') . ' ' . ($modelo ?? ''), ' -'), ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="Sin impresora asociada"
                                    readonly>
                            </div>
                        </div>

                        <!-- ================================================== -->
                        <!-- ASIGNACIÓN DE TÉCNICO (ADMINISTRADOR) -->
                        <!-- ================================================== -->

                        <?php if ($puede_enviar) { ?>

                            <div class="col-md-12">

                                <div class="form-group">

                                    <label>Asignar mantenimiento a soporte técnico</label>

                                    <form action="../app/controllers/mantenimiento/asignar_tecnico.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de asignar este mantenimiento al técnico seleccionado?');">

                                        <input type="hidden"
                                            name="id_mantenimiento"
                                            value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <select name="id_tecnico"
                                            class="form-control mb-2"
                                            required>

                                            <option value="">Seleccione un técnico</option>

                                            <?php foreach ($tecnicos as $tecnico) { ?>

                                                <?php
                                                $cantidad = (int) $tecnico['mantenimientos_activos'];
                                                $disponible = $cantidad < 5;
                                                ?>

                                                <option value="<?php echo (int) $tecnico['id_usuario']; ?>"
                                                    <?php echo !$disponible ? 'disabled' : ''; ?>>

                                                    <?php echo htmlspecialchars($tecnico['nombres'], ENT_QUOTES, 'UTF-8'); ?>

                                                    (<?php echo $cantidad; ?>/5 mantenimientos)

                                                    <?php echo !$disponible ? '- Límite alcanzado' : ''; ?>

                                                </option>

                                            <?php } ?>

                                        </select>

                                        <?php if (empty($tecnicos)) { ?>

                                            <div class="alert alert-warning">
                                                No hay técnicos de soporte registrados.
                                            </div>

                                        <?php } else { ?>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-user-check"></i>
                                                Asignar técnico y enviar
                                            </button>

                                        <?php } ?>

                                    </form>

                                </div>

                            </div>

                        <?php } ?>

                        <!-- ================================================== -->
                        <!-- ACCIONES DE SOPORTE TÉCNICO -->
                        <!-- ================================================== -->

                        <?php if ($puede_atender) { ?>

                            <div class="col-md-12">

                                <div class="form-group">

                                    <label>Atender solicitud</label>
                                    <br>

                                    <form action="../app/controllers/mantenimiento/aceptar.php"
                                        method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('¿Estás seguro de aceptar este mantenimiento?');">

                                        <input type="hidden"
                                            name="id_mantenimiento"
                                            value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i>
                                            Aceptar mantenimiento
                                        </button>

                                    </form>

                                    <form action="../app/controllers/mantenimiento/rechazar.php"
                                        method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('¿Estás seguro de rechazar este mantenimiento?');">

                                        <input type="hidden"
                                            name="id_mantenimiento"
                                            value="<?php echo (int) $id_mantenimiento_get; ?>">

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times"></i>
                                            Rechazar mantenimiento
                                        </button>

                                    </form>

                                </div>

                            </div>

                        <?php } ?>

                        <!-- SOLICITANTE -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Solicitante</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($usuario ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    readonly>
                            </div>
                        </div>

                        <!-- OBSERVACIÓN -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea class="form-control"
                                    rows="4"
                                    readonly><?php echo htmlspecialchars($observacion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <!-- TÉCNICO ASIGNADO -->
                        <?php if (!empty($nombre_tecnico)) { ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Técnico asignado</label>
                                    <input type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($nombre_tecnico, ENT_QUOTES, 'UTF-8'); ?>"
                                        readonly>
                                </div>
                            </div>

                        <?php } ?>

                        <!-- SOLUCIÓN -->
                        <?php if ($estado_actual === 'resuelto' && trim((string) $solucion_mantenimiento) !== '') { ?>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Solución reportada por Soporte</label>
                                    <textarea class="form-control"
                                        rows="4"
                                        readonly><?php echo htmlspecialchars($solucion_mantenimiento, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                        <?php } ?>

                        <!-- COMPONENTES UTILIZADOS -->
                        <?php if (!empty($componentes_usados)) { ?>

                            <div class="col-md-12">

                                <label>Componentes utilizados</label>

                                <div class="table-responsive">

                                    <table class="table table-bordered table-sm">

                                        <thead>
                                            <tr>
                                                <th>Componente</th>
                                                <th>Cantidad</th>
                                                <th>Técnico</th>
                                                <th>Fecha</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php foreach ($componentes_usados as $uso) { ?>

                                                <tr>
                                                    <td>
                                                        <?php echo htmlspecialchars($uso['codigo'] . ' - ' . $uso['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>

                                                    <td><?php echo (int) $uso['cantidad']; ?></td>

                                                    <td>
                                                        <?php echo htmlspecialchars($uso['tecnico'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($uso['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>

                                                    <td>
                                                        <?php echo htmlspecialchars($uso['observacion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                </tr>

                                            <?php } ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                    <!-- BOTONES -->
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>

                    <!-- ACCIONES DEL TÉCNICO -->
                    <?php if ($puede_resolver) { ?>

                        <a href="../componentes/movimientos.php?id_mantenimiento=<?php echo (int) $id_mantenimiento_get; ?>"
                            class="btn btn-info">

                            <i class="fas fa-boxes"></i>
                            Registrar componentes usados

                        </a>

                        <form action="../app/controllers/mantenimiento/finalizar.php"
                            method="POST"
                            class="mt-3">

                            <input type="hidden"
                                name="id_mantenimiento"
                                value="<?php echo (int) $id_mantenimiento_get; ?>">

                            <div class="form-group">

                                <label for="solucion">Solución aplicada</label>

                                <textarea id="solucion"
                                    name="solucion"
                                    class="form-control"
                                    rows="4"
                                    maxlength="5000"
                                    required></textarea>

                            </div>

                            <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('¿Confirmas que este mantenimiento ya fue resuelto?');">

                                <i class="fas fa-check-double"></i>
                                Finalizar mantenimiento

                            </button>

                        </form>

                    <?php } ?>

                    <!-- EDITAR (ADMINISTRADOR) -->
                    <?php if ($es_administrador) { ?>

                        <a href="update.php?id=<?php echo (int) $id_mantenimiento_get; ?>"
                            class="btn btn-success">

                            <i class="fas fa-pencil-alt"></i>
                            Editar

                        </a>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include('../layout/parte2.php'); ?>