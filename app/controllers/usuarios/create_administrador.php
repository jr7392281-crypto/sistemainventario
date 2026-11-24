<?php
include('../../config.php');
include('../../solo_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . $URL . '/usuarios/administradores.php');
    exit;
}

$nombres = trim($_POST['nombres'] ?? '');
$email = trim($_POST['email'] ?? '');
$id_area = (int) ($_POST['id_area'] ?? 0);
$destino = $URL . '/usuarios/administradores.php';

if ($nombres === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $id_area < 1) {
    $_SESSION['mensaje'] = 'Completa los datos del administrador correctamente.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

$validar_area = $pdo->prepare('SELECT id_area FROM tb_areas WHERE id_area = :id_area LIMIT 1');
$validar_area->execute([':id_area' => $id_area]);
if (!$validar_area->fetchColumn()) {
    $_SESSION['mensaje'] = 'El área seleccionada no existe.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

$consulta_rol = $pdo->prepare("SELECT id_rol FROM tb_roles WHERE LOWER(TRIM(rol)) = 'administrador' LIMIT 1");
$consulta_rol->execute();
$id_rol_administrador = (int) $consulta_rol->fetchColumn();

if ($id_rol_administrador < 1) {
    $_SESSION['mensaje'] = 'No existe el rol administrador en la base de datos.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

try {
    // Se guarda solo el hash. La clave sin cifrar se envía y luego se descarta.
    $clave_temporal = rtrim(strtr(base64_encode(random_bytes(12)), '+/', '-_'), '=');
    $crear = $pdo->prepare(
        'INSERT INTO tb_usuarios
            (nombres, email, id_rol, id_area, password_user, email_verificado, debe_cambiar_password)
         VALUES (:nombres, :email, :id_rol, :id_area, :password_user, 0, 1)'
    );
    $crear->execute([
        ':nombres' => $nombres,
        ':email' => $email,
        ':id_rol' => $id_rol_administrador,
        ':id_area' => $id_area,
        ':password_user' => password_hash($clave_temporal, PASSWORD_DEFAULT),
    ]);

    $id_usuario = (int) $pdo->lastInsertId();
    registrar_auditoria($pdo, 'crear', 'administradores', $id_usuario, 'Creó la cuenta de administrador ' . $nombres . '.');
    try {
        require_once __DIR__ . '/../../email_verificacion.php';
        enviar_enlace_verificacion($pdo, [
            'id_usuario' => $id_usuario,
            'nombres' => $nombres,
            'email' => $email,
            'clave_temporal' => $clave_temporal,
        ], $URL);
        $_SESSION['mensaje'] = 'Administrador creado. Se envió el correo con la clave temporal y el enlace de verificación.';
        $_SESSION['icono'] = 'success';
    } catch (Throwable $e) {
        error_log('No se pudo enviar el correo al crear administrador: ' . $e->getMessage());
        $_SESSION['mensaje'] = 'Administrador creado, pero no se pudo enviar el correo.';
        $_SESSION['icono'] = 'warning';
    }
} catch (PDOException $e) {
    error_log('Error al crear administrador: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo crear. Verifica que el correo no esté registrado.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $destino);
exit;
