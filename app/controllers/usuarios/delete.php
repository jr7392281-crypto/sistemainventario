<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$id_usuario = (int) ($_POST['id_usuario'] ?? 0);

if ($id_usuario < 1) {
    $_SESSION['mensaje'] = 'El usuario seleccionado no es valido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

$consulta_actual = $pdo->prepare(
    'SELECT id_usuario FROM tb_usuarios WHERE email = :email LIMIT 1'
);
$consulta_actual->execute([':email' => $_SESSION['sesion_email']]);
$id_usuario_actual = (int) $consulta_actual->fetchColumn();

$consulta_objetivo = $pdo->prepare(
    'SELECT us.id_usuario, us.email, us.nombres, rol.rol
     FROM tb_usuarios us
     INNER JOIN tb_roles rol ON rol.id_rol = us.id_rol
     WHERE us.id_usuario = :id_usuario
     LIMIT 1'
);
$consulta_objetivo->execute([':id_usuario' => $id_usuario]);
$usuario_objetivo = $consulta_objetivo->fetch(PDO::FETCH_ASSOC);

if (!$usuario_objetivo) {
    $_SESSION['mensaje'] = 'El usuario ya no existe.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

$rol_actual = strtolower(trim((string) $rol_sesion));
$rol_objetivo = strtolower(trim($usuario_objetivo['rol']));

if ($id_usuario_actual === $id_usuario) {
    $_SESSION['mensaje'] = 'No puedes eliminar tu propio usuario.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

if (in_array($rol_objetivo, ['administrador', 'super administrador'], true)) {
    $_SESSION['mensaje'] = 'Las cuentas administrativas no se eliminan desde el listado general.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/usuarios/');
    exit;
}

try {
    $sentencia = $pdo->prepare('DELETE FROM tb_usuarios WHERE id_usuario = :id_usuario');
    $ejecucion_correcta = $sentencia->execute([':id_usuario' => $id_usuario]);

    if ($ejecucion_correcta && $sentencia->rowCount() > 0) {
        registrar_auditoria($pdo, 'eliminar', 'usuarios', $id_usuario, 'Eliminó la cuenta de ' . $usuario_objetivo['nombres'] . '.');
        $_SESSION['mensaje'] = 'Se elimino el usuario correctamente.';
        $_SESSION['icono'] = 'success';
    } else {
        $error = $sentencia->errorInfo();
        if (($error[0] ?? '') === '23000') {
            $_SESSION['mensaje'] = 'No se puede eliminar: este usuario tiene mantenimientos o movimientos registrados. Conserva el historial.';
        } else {
            $_SESSION['mensaje'] = 'No se pudo eliminar el usuario. Intentalo nuevamente.';
        }
        $_SESSION['icono'] = 'error';
    }
} catch (PDOException $e) {
    if ((string) $e->getCode() === '23000') {
        $_SESSION['mensaje'] = 'No se puede eliminar: este usuario tiene mantenimientos o movimientos registrados. Conserva el historial.';
    } else {
        $_SESSION['mensaje'] = 'No se pudo eliminar el usuario. Intentalo nuevamente.';
    }
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/usuarios/');
exit;
?>
