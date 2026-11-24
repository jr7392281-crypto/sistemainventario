<?php
include('../../config.php');
require_once __DIR__ . '/../../auditoria.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$token = $_GET['token'] ?? '';
if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
    $_SESSION['mensaje'] = 'El enlace de verificación no es válido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/');
    exit;
}

try {
    $pdo->beginTransaction();
    $consulta = $pdo->prepare(
        'SELECT id_usuario
         FROM tb_email_verification
         WHERE token_hash = :token_hash
           AND fecha_expiracion > NOW()
         LIMIT 1
         FOR UPDATE'
    );
    $consulta->execute([':token_hash' => hash('sha256', $token)]);
    $verificacion = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$verificacion) {
        $pdo->rollBack();
        $_SESSION['mensaje'] = 'El enlace no es válido, ya fue usado o venció. Contacta al administrador.';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/login/');
        exit;
    }

    $actualizar = $pdo->prepare(
        'UPDATE tb_usuarios
         SET email_verificado = 1, email_verificado_at = NOW()
         WHERE id_usuario = :id_usuario'
    );
    $actualizar->execute([':id_usuario' => (int) $verificacion['id_usuario']]);

    $eliminar_token = $pdo->prepare(
        'DELETE FROM tb_email_verification WHERE id_usuario = :id_usuario'
    );
    $eliminar_token->execute([':id_usuario' => (int) $verificacion['id_usuario']]);
    $pdo->commit();
    registrar_auditoria(
        $pdo,
        'verificar_correo',
        'usuarios',
        (int) $verificacion['id_usuario'],
        'Verificó su correo electrónico.',
        (int) $verificacion['id_usuario']
    );

    unset($_SESSION['mensaje'], $_SESSION['icono']);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al verificar correo: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo verificar el correo. Intenta nuevamente.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/login/');
exit;
