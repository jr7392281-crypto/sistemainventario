<?php
include('../../config.php');
include('../../solo_administrador.php');

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$id_usuario = $_POST['id_usuario'];

$sentencia = $pdo->prepare('DELETE FROM tb_usuarios WHERE id_usuario = :id_usuario');
$sentencia->bindParam(':id_usuario', $id_usuario);
$sentencia->execute();

$_SESSION['mensaje'] = 'Se eliminó el usuario correctamente.';
$_SESSION['icono'] = 'success';

header('Location: ' . $URL . '/usuarios/');
?>