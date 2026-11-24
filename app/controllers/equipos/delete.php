<?php include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';
$id_equipo = $_POST['id_equipo'];
$sentencia = $pdo->prepare("DELETE FROM tb_equipos WHERE id_equipo=:id_equipo ");
$sentencia->bindParam('id_equipo', $id_equipo);
if ($sentencia->execute()) {
    if ($sentencia->rowCount() > 0) {
        registrar_auditoria($pdo, 'eliminar', 'equipos', (int) $id_equipo, 'Eliminó el equipo #' . (int) $id_equipo . '.');
    }
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Se elimino el equipo de la manera correcta";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/equipos/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Error no se pudo eliminar el registro en la base de datos";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/equipos/delete.php?id=' . $id_equipo);
}
