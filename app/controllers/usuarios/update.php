
<?php
include('../../config.php');
include('../../solo_administrador.php');

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$id_usuario = (int) ($_POST['id_usuario'] ?? 0);
$nombres = trim($_POST['nombres'] ?? '');
$email = trim($_POST['email'] ?? '');
$rol = (int) ($_POST['rol'] ?? 0);
$id_area = (int) ($_POST['id_area'] ?? 0);
$password_user = $_POST['password_user'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

$consulta_usuario_actual = $pdo->prepare(
    'SELECT r.rol
     FROM tb_usuarios AS u
     INNER JOIN tb_roles AS r ON r.id_rol = u.id_rol
     WHERE u.id_usuario = :id_usuario
     LIMIT 1'
);
$consulta_usuario_actual->execute([':id_usuario' => $id_usuario]);
$rol_usuario_actualizado = strtolower(trim((string) $consulta_usuario_actual->fetchColumn()));

$consulta_rol_nuevo = $pdo->prepare('SELECT rol FROM tb_roles WHERE id_rol = :id_rol LIMIT 1');
$consulta_rol_nuevo->execute([':id_rol' => $rol]);
$rol_nuevo = strtolower(trim((string) $consulta_rol_nuevo->fetchColumn()));
$rol_actual = strtolower(trim((string) $rol_sesion));

if (
    $rol_usuario_actualizado === '' ||
    $rol_nuevo === '' ||
    ($rol_actual === 'administrador' && (
        $rol_usuario_actualizado === 'super administrador' ||
        $rol_nuevo === 'super administrador'
    ))
) {
    $_SESSION['mensaje'] = 'No tienes permiso para modificar ese usuario o asignar ese rol.';
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

if ($password_user !== '' && $password_user !== $password_repeat) {
    $_SESSION['mensaje'] = 'Las contraseñas no coinciden.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

$sql = "UPDATE tb_usuarios
        SET nombres = :nombres,
            email = :email,
            id_rol = :id_rol,
            id_area = :id_area,
            fecha_actualizacion = :fecha_actualizacion";

if ($password_user !== '') {
    $sql .= ', password_user = :password_user';
}

$sql .= ' WHERE id_usuario = :id_usuario';

$sentencia = $pdo->prepare($sql);
$sentencia->bindValue(':nombres', $nombres);
$sentencia->bindValue(':email', $email);
$sentencia->bindValue(':id_rol', $rol, PDO::PARAM_INT);
$sentencia->bindValue(':id_area', $id_area, PDO::PARAM_INT);
$sentencia->bindValue(':fecha_actualizacion', $fechaHora);
$sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($password_user !== '') {
    $password_hash = password_hash($password_user, PASSWORD_DEFAULT);
    $sentencia->bindValue(':password_user', $password_hash);
}

if ($sentencia->execute()) {
    $_SESSION['mensaje'] = 'Se actualizó el usuario correctamente.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/usuarios/');
} else {
    $_SESSION['mensaje'] = 'No se pudo actualizar el usuario.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
}
exit;
?>
