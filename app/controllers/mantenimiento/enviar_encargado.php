
<?php

// ======================================================
// INCLUIR ARCHIVOS NECESARIOS
// ======================================================

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../notificaciones.php';


// ======================================================
// VERIFICAR QUE LA SOLICITUD SEA POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}


// ======================================================
// VERIFICAR PERMISOS
// ======================================================

$rol_actual = strtolower(trim($rol_sesion ?? ''));

if (!in_array($rol_actual, ['administrador', 'super administrador'], true)) {

    $_SESSION['mensaje'] = 'No tienes permiso para enviar esta solicitud.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}


// ======================================================
// OBTENER ID DEL MANTENIMIENTO
// ======================================================

$id_mantenimiento = filter_input(INPUT_POST, 'id_mantenimiento', FILTER_VALIDATE_INT);

if (!$id_mantenimiento || $id_mantenimiento < 1) {

    $_SESSION['mensaje'] = 'El mantenimiento seleccionado no es válido.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}


// ======================================================
// PROCESAR ENVÍO A SOPORTE TÉCNICO
// ======================================================

try {

    // Iniciar transacción.
    $pdo->beginTransaction();


    // --------------------------------------------------
    // BUSCAR Y BLOQUEAR EL MANTENIMIENTO
    // --------------------------------------------------

    $consulta = $pdo->prepare(
        "SELECT id_mantenimiento, estado
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

    $estado_actual = strtolower(trim($mantenimiento['estado'] ?? 'reportado'));

    if (!in_array($estado_actual, ['reportado', ''], true)) {
        throw new Exception('Esta solicitud ya fue enviada o procesada.');
    }


    // --------------------------------------------------
    // ACTUALIZAR ESTADO A ENVIADO
    // --------------------------------------------------

    $actualizar = $pdo->prepare(
        "UPDATE tb_mantenimientos
         SET estado = 'enviado'
         WHERE id_mantenimiento = :id"
    );

    $actualizar->execute([
        ':id' => $id_mantenimiento
    ]);


    // --------------------------------------------------
    // NOTIFICAR A SOPORTE TÉCNICO
    // --------------------------------------------------

    $total_notificaciones = notificarRoles(
        $pdo,
        ['soporte tecnico'],
        (int) $id_usuario_sesion,
        (int) $id_mantenimiento,
        'nuevo_mantenimiento',
        'Nuevo mantenimiento',
        'Tienes un nuevo mantenimiento pendiente de atención. Revisa sus detalles.'
    );


    // --------------------------------------------------
    // VERIFICAR QUE SE HAYA CREADO LA NOTIFICACIÓN
    // --------------------------------------------------

    if ($total_notificaciones < 1) {
        throw new Exception(
            'No se encontraron usuarios de soporte técnico o no se creó ninguna notificación.'
        );
    }


    // --------------------------------------------------
    // CONFIRMAR TRANSACCIÓN
    // --------------------------------------------------

    $pdo->commit();

    $_SESSION['mensaje'] = 'La solicitud fue enviada a Soporte técnico correctamente.';
    $_SESSION['icono'] = 'success';


} catch (Throwable $e) {

    // Revertir cambios si ocurrió algún error.
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Error al enviar mantenimiento a soporte técnico: ' . $e->getMessage());

    $_SESSION['mensaje'] = 'Error al enviar la solicitud: ' . $e->getMessage();
    $_SESSION['icono'] = 'error';
}


// ======================================================
// REGRESAR AL DETALLE DEL MANTENIMIENTO
// ======================================================

header(
    'Location: ' . $URL . '/mantenimiento/show.php?id=' . (int) $id_mantenimiento
);

exit;
