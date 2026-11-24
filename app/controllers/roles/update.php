<?php
include("../../config.php");
include("../../solo_super_administrador.php");
require_once __DIR__ . '/../../auditoria.php';

$id_rol = $_POST['id_rol'];
$rol = $_POST['rol'];

$sentencia = $pdo->prepare("UPDATE tb_roles 
                            SET rol=:rol,
                            fecha_actualizacion =:fecha_actualizacion
                            WHERE id_rol = :id_rol");
$sentencia->bindParam('rol', $rol);
$sentencia->bindParam('fecha_actualizacion', $fechaHora);
$sentencia->bindParam('id_rol', $id_rol);

if ($sentencia->execute()) {
    registrar_auditoria($pdo, 'editar', 'roles', (int) $id_rol, 'Actualizó el nombre del rol a ' . $rol . '.');
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Se actualizo el rol de forma correcta";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/roles/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = "Error no se pudo actualizar en la base de datos";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/roles/update.php?id=' .$id_rol);
}
?>
