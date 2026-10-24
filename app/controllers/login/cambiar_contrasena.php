<?php
include('../../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/login/');
    exit;
}

$token = $_POST['token'] ?? '';
$password_user = $_POST['password_user'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

if (
    !is_string($token) ||
    !preg_match('/^[a-f0-9]{64}$/', $token) ||
    !is_string($password_user) ||
    strlen($password_user) < 8 ||
    $password_user !== $password_repeat
) {
    $_SESSION['mensaje'] = 'Revisa el enlace y asegúrate de que las contraseñas coincidan y tengan al menos 8 caracteres.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/restablecer.php?token=' . urlencode(is_string($token) ? $token : ''));
    exit;
}

try {
    $pdo->beginTransaction();
    $consulta = $pdo->prepare(
        'SELECT id_reset, id_usuario
         FROM tb_password_resets
         WHERE token_hash = :token_hash
           AND fecha_uso IS NULL
           AND fecha_expiracion > NOW()
         LIMIT 1
         FOR UPDATE'
    );
    $consulta->execute([':token_hash' => hash('sha256', $token)]);
    $reset = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        $pdo->rollBack();
        $_SESSION['mensaje'] = 'El enlace no es válido o ya venció. Solicita uno nuevo.';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/login/olvidar.php');
        exit;
    }

    $actualizar_password = $pdo->prepare(
        'UPDATE tb_usuarios SET password_user = :password_user WHERE id_usuario = :id_usuario'
    );
    $actualizar_password->execute([
        ':password_user' => password_hash($password_user, PASSWORD_DEFAULT),
        ':id_usuario' => $reset['id_usuario'],
    ]);

    $marcar_tokens = $pdo->prepare(
        'UPDATE tb_password_resets
         SET fecha_uso = NOW()
         WHERE id_usuario = :id_usuario AND fecha_uso IS NULL'
    );
    $marcar_tokens->execute([':id_usuario' => $reset['id_usuario']]);
    $pdo->commit();

    $_SESSION['mensaje'] = 'La contraseña se cambió correctamente. Ya puedes iniciar sesión.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/login/');
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al cambiar contraseña: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo cambiar la contraseña. Inténtalo de nuevo.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/olvidar.php');
    exit;
}
