<?php
include('../../config.php');
include('../../solo_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$id_usuario = (int) ($_POST['id_usuario'] ?? 0);
$destino = $URL . '/usuarios/administradores.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || $id_usuario < 1) {
    $_SESSION['mensaje'] = 'Selecciona un administrador válido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

$consulta = $pdo->prepare(
    "SELECT u.id_usuario, u.nombres
     FROM tb_usuarios u
     INNER JOIN tb_roles r ON r.id_rol = u.id_rol
     WHERE u.id_usuario = :id_usuario AND LOWER(TRIM(r.rol)) = 'administrador'
     LIMIT 1"
);
$consulta->execute([':id_usuario' => $id_usuario]);
$administrador_objetivo = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$administrador_objetivo) {
    $_SESSION['mensaje'] = 'La cuenta seleccionada no es un administrador.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $destino);
    exit;
}

try {
    $eliminar = $pdo->prepare('DELETE FROM tb_usuarios WHERE id_usuario = :id_usuario');
    $eliminar->execute([':id_usuario' => $id_usuario]);
    if ($eliminar->rowCount() > 0) {
        registrar_auditoria($pdo, 'eliminar', 'administradores', $id_usuario, 'Eliminó la cuenta de administrador ' . $administrador_objetivo['nombres'] . '.');
    }
    $_SESSION['mensaje'] = 'Administrador eliminado correctamente.';
    $_SESSION['icono'] = 'success';
} catch (PDOException $e) {
    error_log('Error al eliminar administrador: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se puede eliminar porque tiene movimientos o mantenimientos asociados.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $destino);
exit;
