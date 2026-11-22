<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../notificaciones.php';

// Verificar que el formulario se haya enviado mediante POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}

if (strtolower(trim((string) $rol_sesion)) !== 'personal') {
    $_SESSION['mensaje'] = 'Solo el personal puede registrar una solicitud de mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// Obtener los datos del formulario.
$id_equipo = ($_POST['id_equipo'] ?? '') === ''
    ? null
    : (int) $_POST['id_equipo'];

$id_impresora = ($_POST['id_impresora'] ?? '') === ''
    ? null
    : (int) $_POST['id_impresora'];

$id_usuario = (int) $id_usuario_sesion;
$fecha = $_POST['fecha'] ?? '';
$tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? '';
$observacion = trim($_POST['observacion'] ?? '');

// Verificar que se seleccione un equipo o una impresora, pero no ambos.
if (($id_equipo === null) === ($id_impresora === null)) {
    $_SESSION['mensaje'] = 'Selecciona un equipo o una impresora, pero no ambos.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}

try {

    $pdo->beginTransaction();

    // Preparar el registro del mantenimiento.
    $sentencia = $pdo->prepare(
        "INSERT INTO tb_mantenimientos
            (
                id_equipo,
                id_impresora,
                id_usuario,
                fecha,
                tipo_mantenimiento,
                observacion
            )
         VALUES
            (
                :id_equipo,
                :id_impresora,
                :id_usuario,
                :fecha,
                :tipo_mantenimiento,
                :observacion
            )"
    );

    $sentencia->bindValue(
        ':id_equipo',
        $id_equipo,
        $id_equipo === null ? PDO::PARAM_NULL : PDO::PARAM_INT
    );

    $sentencia->bindValue(
        ':id_impresora',
        $id_impresora,
        $id_impresora === null ? PDO::PARAM_NULL : PDO::PARAM_INT
    );

    $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $sentencia->bindValue(':fecha', $fecha);
    $sentencia->bindValue(':tipo_mantenimiento', $tipo_mantenimiento);
    $sentencia->bindValue(':observacion', $observacion);

    $sentencia->execute();

    // Obtener el ID del mantenimiento registrado.
    $id_mantenimiento = (int) $pdo->lastInsertId();

    // Avisar a los administradores para que revisen y deriven el reporte.
    notificarRoles(
        $pdo,
        ['administrador', 'super administrador'],
        $id_usuario,
        $id_mantenimiento,
        'nuevo_mantenimiento_reportado',
        'Nuevo reporte de mantenimiento',
        'El personal registró una solicitud que requiere revisión.'
    );

    $pdo->commit();

    // Mensaje de confirmación.
    $_SESSION['mensaje'] = 'Se registró el mantenimiento correctamente.';
    $_SESSION['icono'] = 'success';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Error al registrar mantenimiento: ' . $e->getMessage());

    $_SESSION['mensaje'] = 'No se pudo registrar el mantenimiento.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}
