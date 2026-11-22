<?php
include('../../config.php');
include('../../solo_administrador.php');

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$nombres = trim($_POST['nombres'] ?? '');
$email = trim($_POST['email'] ?? '');
$rol = (int) ($_POST['rol'] ?? 0);
$id_area = (int) ($_POST['id_area'] ?? 0);
$password_user = $_POST['password_user'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

$consulta_rol_nuevo = $pdo->prepare('SELECT rol FROM tb_roles WHERE id_rol = :id_rol LIMIT 1');
$consulta_rol_nuevo->execute([':id_rol' => (int) $rol]);
$rol_nuevo = strtolower(trim((string) $consulta_rol_nuevo->fetchColumn()));

$rol_actual = strtolower(trim((string) $rol_sesion));

if ($rol_nuevo === '' || ($rol_actual === 'administrador' && $rol_nuevo === 'super administrador')) {
    $_SESSION['mensaje'] = 'No tienes permiso para asignar ese rol.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

if ($id_area < 1) {
    $_SESSION['mensaje'] = 'Debes seleccionar un área.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje'] = 'Ingresa un correo válido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

if ($password_user !== $password_repeat) {
    $_SESSION['mensaje'] = 'Las contraseñas no coinciden.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

try {
    $password_hash = password_hash($password_user, PASSWORD_DEFAULT);
    $sentencia = $pdo->prepare(
        'INSERT INTO tb_usuarios
            (nombres, email, id_rol, id_area, password_user, email_verificado)
         VALUES (:nombres, :email, :id_rol, :id_area, :password_user, 0)'
    );
    $sentencia->execute([
        ':nombres' => $nombres,
        ':email' => $email,
        ':id_rol' => $rol,
        ':id_area' => $id_area,
        ':password_user' => $password_hash,
    ]);

    $id_usuario = (int) $pdo->lastInsertId();
    try {
        require_once __DIR__ . '/../../email_verificacion.php';
        enviar_enlace_verificacion($pdo, [
            'id_usuario' => $id_usuario,
            'nombres' => $nombres,
            'email' => $email,
        ], $URL);

        $_SESSION['mensaje'] = 'Usuario creado. Se envió el enlace para verificar su correo.';
        $_SESSION['icono'] = 'success';
    } catch (Throwable $e) {
        error_log('No se pudo enviar la verificación al crear usuario: ' . $e->getMessage());
        $_SESSION['mensaje'] = 'Usuario creado, pero no se pudo enviar el enlace de verificación. Contacta al administrador.';
        $_SESSION['icono'] = 'warning';
    }
} catch (PDOException $e) {
    error_log('Error al crear usuario: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo crear el usuario. Revisa que el correo no esté registrado.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/usuarios/');
exit;
