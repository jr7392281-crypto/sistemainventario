
<?php

include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../notificaciones.php';

// ======================================================
// VERIFICAR MÉTODO POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// ======================================================
// VERIFICAR PERMISOS
// SOLO SOPORTE TÉCNICO PUEDE ACEPTAR
// ======================================================

$rol_actual = strtolower(trim($rol_sesion ?? ''));

if ($rol_actual !== 'soporte tecnico') {

    $_SESSION['mensaje'] = 'No tienes permiso para aceptar mantenimientos.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// ======================================================
// OBTENER ID DEL MANTENIMIENTO
// ======================================================

$id_mantenimiento = filter_input(
    INPUT_POST,
    'id_mantenimiento',
    FILTER_VALIDATE_INT
);

if (!$id_mantenimiento || $id_mantenimiento < 1) {

    $_SESSION['mensaje'] = 'El mantenimiento seleccionado no es válido.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// ======================================================
// PROCESAR ACEPTACIÓN
// ======================================================

try {

    $pdo->beginTransaction();

    // --------------------------------------------------
    // OBTENER MANTENIMIENTO Y USUARIO SOLICITANTE
    // --------------------------------------------------

    $consulta = $pdo->prepare(
        "SELECT id_mantenimiento, id_usuario, estado
         FROM tb_mantenimientos
         WHERE id_mantenimiento = :id
         FOR UPDATE"
    );

    $consulta->execute([
        ':id' => $id_mantenimiento
    ]);

    $mantenimiento = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento) {
        throw new Exception('No se encontró el mantenimiento.');
    }

    // --------------------------------------------------
    // VERIFICAR ESTADO ACTUAL
    // --------------------------------------------------

    $estado_actual = strtolower(
        trim($mantenimiento['estado'] ?? '')
    );

    if ($estado_actual !== 'enviado') {
        throw new Exception(
            'Este mantenimiento no está pendiente de atención o ya fue procesado.'
        );
    }

    // --------------------------------------------------
    // ACTUALIZAR ESTADO
    // --------------------------------------------------

    $actualizar = $pdo->prepare(
        "UPDATE tb_mantenimientos
         SET estado = 'aceptado'
         WHERE id_mantenimiento = :id"
    );

    $actualizar->execute([
        ':id' => $id_mantenimiento
    ]);

    // --------------------------------------------------
    // NOTIFICAR AL USUARIO QUE REGISTRÓ EL MANTENIMIENTO
    // --------------------------------------------------

    crearNotificacion(
        $pdo,
        (int) $mantenimiento['id_usuario'],
        (int) $id_usuario_sesion,
        (int) $id_mantenimiento,
        'mantenimiento_aceptado',
        'Solicitud de mantenimiento aceptada',
        'Tu solicitud de mantenimiento ha sido aceptada por Soporte técnico y será atendida.'
    );

    // --------------------------------------------------
    // CONFIRMAR TRANSACCIÓN
    // --------------------------------------------------

    $pdo->commit();

    $_SESSION['mensaje'] = 'El mantenimiento fue aceptado correctamente.';
    $_SESSION['icono'] = 'success';

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Error al aceptar mantenimiento: ' . $e->getMessage());

    $_SESSION['mensaje'] = 'No se pudo aceptar el mantenimiento.';
    $_SESSION['icono'] = 'error';
}

// ======================================================
// REGRESAR AL DETALLE
// ======================================================

header(
    'Location: ' . $URL . '/mantenimiento/show.php?id=' . (int) $id_mantenimiento
);

exit;
