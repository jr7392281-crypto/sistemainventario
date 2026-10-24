<?php
include("../../config.php");
include("../../solo_administrador.php");

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
