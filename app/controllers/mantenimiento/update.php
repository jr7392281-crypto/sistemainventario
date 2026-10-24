<?php
include('../../config.php');
include('../../solo_administrador.php');

$id_mantenimiento = $_POST['id_mantenimiento'];
$fecha = $_POST['fecha'];
$tipo_mantenimiento = $_POST['tipo_mantenimiento'];
$observacion = $_POST['observacion'];

$sentencia = $pdo->prepare("UPDATE tb_mantenimientos SET
    fecha = :fecha,
    tipo_mantenimiento = :tipo_mantenimiento,
    observacion = :observacion
    WHERE id_mantenimiento = :id_mantenimiento");

$sentencia->bindParam(':fecha', $fecha);
$sentencia->bindParam(':tipo_mantenimiento', $tipo_mantenimiento);
$sentencia->bindParam(':observacion', $observacion);
$sentencia->bindParam(':id_mantenimiento', $id_mantenimiento);

if ($sentencia->execute()) {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Se actualizó el mantenimiento correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo actualizar el mantenimiento';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/update.php?id=' . $id_mantenimiento);
}
