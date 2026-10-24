<?php
include('../../config.php');
include('../../solo_administrador.php');

$id_mantenimiento = $_POST['id_mantenimiento'];
$sentencia = $pdo->prepare(
    'DELETE FROM tb_mantenimientos WHERE id_mantenimiento = :id_mantenimiento'
);
$sentencia->bindParam(':id_mantenimiento', $id_mantenimiento);

if ($sentencia->execute()) {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Se eliminó el mantenimiento correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo eliminar el mantenimiento';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/delete.php?id=' . $id_mantenimiento);
}
