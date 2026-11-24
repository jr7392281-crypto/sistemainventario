
<?php

include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../notificaciones.php';
require_once __DIR__ . '/../../auditoria.php';

// ======================================================
// VERIFICAR MÉTODO POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// ======================================================
// VERIFICAR PERMISOS
// SOLO SOPORTE TÉCNICO PUEDE FINALIZAR
// ======================================================

$rol_actual = strtolower(trim($rol_sesion ?? ''));

if ($rol_actual !== 'soporte tecnico') {

    $_SESSION['mensaje'] = 'No tienes permiso para finalizar mantenimientos.';
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
$solucion = is_string($_POST['solucion'] ?? null)
    ? trim($_POST['solucion'])
    : '';

if (!$id_mantenimiento || $id_mantenimiento < 1 || $solucion === '') {

    $_SESSION['mensaje'] = 'Revisa el mantenimiento y escribe la solución aplicada.';
    $_SESSION['icono'] = 'error';

    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

// ======================================================
// PROCESAR FINALIZACIÓN
// ======================================================

try {

    $pdo->beginTransaction();

    // Obtener mantenimiento y usuario solicitante.
    $consulta = $pdo->prepare(
        "SELECT id_mantenimiento, id_usuario, estado, id_tecnico_asignado
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

    // Verificar que esté aceptado.
    $estado_actual = strtolower(
        trim($mantenimiento['estado'] ?? '')
    );

    if ($estado_actual !== 'aceptado') {
        throw new Exception(
            'Este mantenimiento no está aceptado o ya fue finalizado.'
        );
    }

    if ((int) ($mantenimiento['id_tecnico_asignado'] ?? 0) !== (int) $id_usuario_sesion) {
        throw new Exception('Solo el técnico que aceptó este mantenimiento puede finalizarlo.');
    }

    // ==================================================
    // ACTUALIZAR ESTADO A RESUELTO
    // ==================================================

    $actualizar = $pdo->prepare(
        "UPDATE tb_mantenimientos
         SET estado = 'resuelto', solucion = :solucion
         WHERE id_mantenimiento = :id AND id_tecnico_asignado = :id_tecnico"
    );

    $actualizar->execute([
        ':solucion' => $solucion,
        ':id' => $id_mantenimiento,
        ':id_tecnico' => (int) $id_usuario_sesion
    ]);

    if ($actualizar->rowCount() !== 1) {
        throw new Exception('No se pudo cerrar el mantenimiento asignado.');
    }

    // ==================================================
    // NOTIFICAR AL PERSONAL SOLICITANTE
    // ==================================================

    crearNotificacion(
        $pdo,
        (int) $mantenimiento['id_usuario'],
        (int) $id_usuario_sesion,
        (int) $id_mantenimiento,
        'mantenimiento_resuelto',
        'Mantenimiento finalizado',
        'Tu mantenimiento fue resuelto por Soporte técnico.'
    );

    // ==================================================
    // NOTIFICAR AL ADMINISTRADOR Y SUPERADMINISTRADOR
    // ==================================================

    notificarRoles(
        $pdo,
        ['administrador', 'super administrador'],
        (int) $id_usuario_sesion,
        (int) $id_mantenimiento,
        'mantenimiento_resuelto_admin',
        'Mantenimiento finalizado',
        'Soporte técnico ha finalizado un mantenimiento. Puedes revisar sus detalles.'
    );

    // ==================================================
    // CONFIRMAR TRANSACCIÓN
    // ==================================================

    $pdo->commit();
    registrar_auditoria($pdo, 'resolver', 'mantenimiento', (int) $id_mantenimiento, 'Soporte técnico marcó el mantenimiento como resuelto.');

    $_SESSION['mensaje'] = 'El mantenimiento fue finalizado correctamente. Se notificó al personal y a los administradores.';
    $_SESSION['icono'] = 'success';

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Error al finalizar mantenimiento: ' . $e->getMessage());

    $_SESSION['mensaje'] = 'No se pudo finalizar el mantenimiento.';
    $_SESSION['icono'] = 'error';
}

// ======================================================
// REGRESAR AL DETALLE DEL MANTENIMIENTO
// ======================================================

header(
    'Location: ' . $URL . '/mantenimiento/show.php?id=' . (int) $id_mantenimiento
);

exit;
