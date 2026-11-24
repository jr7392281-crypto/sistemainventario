<?php
include('../../config.php');
require_once __DIR__ . '/../../auditoria.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . $URL . '/login/');
    exit;
}

$email = trim((string) ($_SESSION['sesion_email'] ?? ''));
$password_user = $_POST['password_user'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

if ($email === '') {
    header('Location: ' . $URL . '/login/');
    exit;
}

if (!is_string($password_user) || !is_string($password_repeat)
    || strlen($password_user) < 8 || $password_user !== $password_repeat) {
    $_SESSION['mensaje'] = 'Las contraseñas deben coincidir y tener al menos 8 caracteres.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/nueva_contrasena_temporal.php');
    exit;
}

try {
    $consulta = $pdo->prepare(
        'SELECT id_usuario, debe_cambiar_password
         FROM tb_usuarios WHERE email = :email LIMIT 1'
    );
    $consulta->execute([':email' => $email]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        session_unset();
        session_destroy();
        header('Location: ' . $URL . '/login/');
        exit;
    }

    if ((int) $usuario['debe_cambiar_password'] !== 1) {
        header('Location: ' . $URL . '/index.php');
        exit;
    }

    $actualizar = $pdo->prepare(
        'UPDATE tb_usuarios
         SET password_user = :password_user,
             debe_cambiar_password = 0,
             fecha_actualizacion = :fecha_actualizacion
         WHERE id_usuario = :id_usuario'
    );
    $actualizar->execute([
        ':password_user' => password_hash($password_user, PASSWORD_DEFAULT),
        ':fecha_actualizacion' => $fechaHora,
        ':id_usuario' => (int) $usuario['id_usuario'],
    ]);

    session_regenerate_id(true);
    registrar_auditoria(
        $pdo,
        'cambiar_contrasena_temporal',
        'autenticacion',
        (int) $usuario['id_usuario'],
        'Reemplazó la clave temporal por una contraseña propia.',
        (int) $usuario['id_usuario']
    );

    $_SESSION['mensaje'] = 'Contraseña creada correctamente.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/index.php');
    exit;
} catch (Throwable $e) {
    error_log('Error al guardar la nueva contraseña: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo guardar la contraseña. Inténtalo nuevamente.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/nueva_contrasena_temporal.php');
    exit;
}
