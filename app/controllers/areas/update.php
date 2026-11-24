<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$id_area = $_POST['id_area'];
$nombre = $_POST['nombre'];

$sentencia = $pdo->prepare("UPDATE tb_areas 
                            SET nombre=:nombre,
                            fecha_actualizacion=:fecha_actualizacion
                            WHERE id_area = :id_area");
$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':fecha_actualizacion', $fechaHora);
$sentencia->bindParam(':id_area', $id_area);

if ($sentencia->execute()) {
    registrar_auditoria($pdo, 'editar', 'areas', (int) $id_area, 'Actualizó el área a ' . $nombre . '.');
    $_SESSION['mensaje'] = 'Se actualizó el área de forma correcta';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/areas/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Error no se pudo actualizar en la base de datos';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/update.php?id=' . $id_area);
}
?>
