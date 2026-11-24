<?php
include('../../config.php');
include('../../solo_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$rol=$_POST['rol'];
$sentencia =$pdo->prepare("INSERT INTO tb_roles
(rol,fecha_registro)
VALUES(:rol, :fecha_registro)");

$sentencia->bindParam('rol',$rol);
$sentencia->bindParam('fecha_registro',$fechaHora);
if ($sentencia->execute()){
    $id_rol = (int) $pdo->lastInsertId();
    registrar_auditoria($pdo, 'crear', 'roles', $id_rol, 'Creó el rol ' . $rol . '.');
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['mensaje'] = "se registro el rol de forma correcta";
$_SESSION['icono'] = "success";
header('location:'.$URL.'/roles/');
}else{
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['mensaje'] ="Error al registrar";
$_SESSION['icono'] = "error";
header('location:'.$URL.'/roles/create.php');
}
?>
