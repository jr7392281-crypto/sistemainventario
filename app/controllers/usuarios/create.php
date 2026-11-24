<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$nombres = trim($_POST['nombres'] ?? '');
$email = trim($_POST['email'] ?? '');
$rol = (int) ($_POST['rol'] ?? 0);
$id_area = (int) ($_POST['id_area'] ?? 0);

$consulta_rol_nuevo = $pdo->prepare('SELECT rol FROM tb_roles WHERE id_rol = :id_rol LIMIT 1');
$consulta_rol_nuevo->execute([':id_rol' => $rol]);
$rol_nuevo = strtolower(trim((string) $consulta_rol_nuevo->fetchColumn()));

if ($nombres === '' || $rol_nuevo === '' || in_array($rol_nuevo, ['administrador', 'super administrador'], true)) {
    $_SESSION['mensaje'] = 'Completa los datos y selecciona un rol válido para este listado.';
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

try {
    // Se guarda solo el hash. La clave sin cifrar se envía y luego se descarta.
    $clave_temporal = rtrim(strtr(base64_encode(random_bytes(12)), '+/', '-_'), '=');
    $sentencia = $pdo->prepare(
        'INSERT INTO tb_usuarios
            (nombres, email, id_rol, id_area, password_user, email_verificado, debe_cambiar_password)
         VALUES (:nombres, :email, :id_rol, :id_area, :password_user, 0, 1)'
    );
    $sentencia->execute([
        ':nombres' => $nombres,
        ':email' => $email,
        ':id_rol' => $rol,
        ':id_area' => $id_area,
        ':password_user' => password_hash($clave_temporal, PASSWORD_DEFAULT),
    ]);

    $id_usuario = (int) $pdo->lastInsertId();
    registrar_auditoria($pdo, 'crear', 'usuarios', $id_usuario, 'Creó la cuenta de ' . $nombres . ' con rol ' . $rol_nuevo . '.');

    try {
        require_once __DIR__ . '/../../email_verificacion.php';
        enviar_enlace_verificacion($pdo, [
            'id_usuario' => $id_usuario,
            'nombres' => $nombres,
            'email' => $email,
            'clave_temporal' => $clave_temporal,
        ], $URL);
        $_SESSION['mensaje'] = 'Usuario creado. Se envió el correo con la clave temporal y el enlace de verificación.';
        $_SESSION['icono'] = 'success';
    } catch (Throwable $e) {
        error_log('No se pudo enviar el correo de acceso al crear usuario: ' . $e->getMessage());
        $_SESSION['mensaje'] = 'Usuario creado, pero no se pudo enviar el correo. Contacta al administrador.';
        $_SESSION['icono'] = 'warning';
    }
} catch (PDOException $e) {
    error_log('Error al crear usuario: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo crear el usuario. Revisa que el correo no esté registrado.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/usuarios/');
exit;
