
<?php

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../notificaciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$rol_actual = strtolower(trim($rol_sesion ?? ''));

if (!in_array($rol_actual, ['administrador', 'super administrador'], true)) {
    $_SESSION['mensaje'] = 'No tienes permiso para asignar técnicos.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$id_mantenimiento = filter_input(INPUT_POST, 'id_mantenimiento', FILTER_VALIDATE_INT);
$id_tecnico = filter_input(INPUT_POST, 'id_tecnico', FILTER_VALIDATE_INT);

if (!$id_mantenimiento || !$id_tecnico || $id_mantenimiento < 1 || $id_tecnico < 1) {
    $_SESSION['mensaje'] = 'Los datos de asignación no son válidos.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

try {

    $pdo->beginTransaction();

    // BLOQUEAR MANTENIMIENTO

    $consulta = $pdo->prepare(
        "SELECT id_mantenimiento, estado
         FROM tb_mantenimientos
         WHERE id_mantenimiento = :id
         FOR UPDATE"
    );

    $consulta->execute([':id' => $id_mantenimiento]);

    $mantenimiento = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento) {
        throw new Exception('No se encontró el mantenimiento.');
    }

    $estado = strtolower(trim($mantenimiento['estado'] ?? ''));

    if (!in_array($estado, ['reportado', 'rechazado', ''], true)) {
        throw new Exception('Este mantenimiento ya fue enviado o no puede asignarse.');
    }

    // BLOQUEAR Y VERIFICAR TÉCNICO

    $consulta = $pdo->prepare(
        "SELECT u.id_usuario, u.nombres
         FROM tb_usuarios u
         INNER JOIN tb_roles r ON r.id_rol = u.id_rol
         WHERE u.id_usuario = :id
         AND LOWER(TRIM(r.rol)) = 'soporte tecnico'
         FOR UPDATE"
    );

    $consulta->execute([':id' => $id_tecnico]);

    $tecnico = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$tecnico) {
        throw new Exception('El técnico seleccionado no existe.');
    }

    // CONTAR MANTENIMIENTOS ACTIVOS

    $consulta = $pdo->prepare(
        "SELECT COUNT(*)
         FROM tb_mantenimientos
         WHERE id_tecnico_asignado = :id
         AND LOWER(TRIM(estado)) IN ('enviado', 'aceptado')"
    );

    $consulta->execute([':id' => $id_tecnico]);

    $total_activos = (int)$consulta->fetchColumn();

    if ($total_activos >= 5) {
        throw new Exception('El técnico ya alcanzó el límite de 5 mantenimientos.');
    }

    // ASIGNAR

    $actualizar = $pdo->prepare(
        "UPDATE tb_mantenimientos
         SET estado = 'enviado',
             id_tecnico_asignado = :tecnico,
             solucion = NULL
         WHERE id_mantenimiento = :id"
    );

    $actualizar->execute([
        ':tecnico' => $id_tecnico,
        ':id' => $id_mantenimiento
    ]);

    // NOTIFICAR SOLAMENTE AL TÉCNICO ELEGIDO

    crearNotificacion(
        $pdo,
        (int)$id_tecnico,
        (int)$id_usuario_sesion,
        (int)$id_mantenimiento,
        'nuevo_mantenimiento',
        'Nuevo mantenimiento asignado',
        'Se te ha asignado un nuevo mantenimiento. Revisa sus detalles.'
    );

    $pdo->commit();

    $_SESSION['mensaje'] =
        'Mantenimiento asignado correctamente a ' . $tecnico['nombres'];

    $_SESSION['icono'] = 'success';

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Error al asignar técnico: ' . $e->getMessage());

    $_SESSION['mensaje'] = 'Error al asignar: ' . $e->getMessage();
    $_SESSION['icono'] = 'error';
}

header(
    'Location: ' . $URL . '/mantenimiento/show.php?id=' . (int)$id_mantenimiento
);

exit;
