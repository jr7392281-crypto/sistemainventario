<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';
$id_impresora = $_POST['id_impresora'];
$sentencia = $pdo->prepare("DELETE FROM tb_impresoras WHERE id_impresora = :id_impresora");
$sentencia->bindParam(':id_impresora', $id_impresora);
if ($sentencia->execute()) {
    if ($sentencia->rowCount() > 0) {
        registrar_auditoria($pdo, 'eliminar', 'impresoras', (int) $id_impresora, 'Eliminó la impresora #' . (int) $id_impresora . '.');
    }
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Se eliminó la impresora de forma correcta";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/impresoras/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Error, no se pudo eliminar el registro de la base de datos";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/impresoras/delete.php?id=' . $id_impresora);
}
?>
