<?php
include('../../config.php');
include('../../solo_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . $URL . '/usuarios/administradores.php');
    exit;
}

$id_usuario = (int) ($_POST['id_usuario'] ?? 0);
$nombres = trim($_POST['nombres'] ?? '');
$email = trim($_POST['email'] ?? '');
$id_area = (int) ($_POST['id_area'] ?? 0);
$password_user = (string) ($_POST['password_user'] ?? '');
$password_repeat = (string) ($_POST['password_repeat'] ?? '');
$destino = $URL . '/usuarios/administradores.php';

$consulta = $pdo->prepare(
    "SELECT u.email, u.nombres
     FROM tb_usuarios u
     INNER JOIN tb_roles r ON r.id_rol = u.id_rol
     WHERE u.id_usuario = :id_usuario AND LOWER(TRIM(r.rol)) = 'administrador'
     LIMIT 1"
);
$consulta->execute([':id_usuario' => $id_usuario]);
$administrador_actual = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$administrador_actual) {
    $_SESSION['mensaje'] = 'La cuenta seleccionada no es un administrador.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

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

if (($password_user !== '' && strlen($password_user) < 8) || $password_user !== $password_repeat) {
    $_SESSION['mensaje'] = 'Si cambias la contraseña, debe tener 8 caracteres como mínimo y ambos campos deben coincidir.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

$cambio_email = strtolower(trim((string) $administrador_actual['email'])) !== strtolower($email);
$sql = 'UPDATE tb_usuarios
        SET nombres = :nombres,
            email = :email,
            id_area = :id_area,
            fecha_actualizacion = :fecha_actualizacion';

if ($password_user !== '') {
    $sql .= ', password_user = :password_user';
}
if ($cambio_email) {
    $sql .= ', email_verificado = 0, email_verificado_at = NULL';
}
$sql .= ' WHERE id_usuario = :id_usuario';

try {
    $actualizar = $pdo->prepare($sql);
    $actualizar->bindValue(':nombres', $nombres);
    $actualizar->bindValue(':email', $email);
    $actualizar->bindValue(':id_area', $id_area, PDO::PARAM_INT);
    $actualizar->bindValue(':fecha_actualizacion', $fechaHora);
    $actualizar->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    if ($password_user !== '') {
        $actualizar->bindValue(':password_user', password_hash($password_user, PASSWORD_DEFAULT));
    }
    $actualizar->execute();
    registrar_auditoria($pdo, 'editar', 'administradores', $id_usuario, 'Actualizó la cuenta de administrador ' . $nombres . '.');

    if ($cambio_email) {
        try {
            require_once __DIR__ . '/../../email_verificacion.php';
            enviar_enlace_verificacion($pdo, [
                'id_usuario' => $id_usuario,
                'nombres' => $nombres,
                'email' => $email,
            ], $URL);
            $_SESSION['mensaje'] = 'Administrador actualizado; se envió el enlace para verificar el nuevo correo.';
            $_SESSION['icono'] = 'success';
        } catch (Throwable $e) {
            error_log('No se pudo enviar la verificación al cambiar correo del administrador: ' . $e->getMessage());
            $_SESSION['mensaje'] = 'Administrador actualizado, pero no se pudo enviar el enlace de verificación.';
            $_SESSION['icono'] = 'warning';
        }
    } else {
        $_SESSION['mensaje'] = 'Administrador actualizado correctamente.';
        $_SESSION['icono'] = 'success';
    }
} catch (PDOException $e) {
    error_log('Error al actualizar administrador: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo actualizar. Verifica que el correo no esté registrado.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $destino);
exit;
