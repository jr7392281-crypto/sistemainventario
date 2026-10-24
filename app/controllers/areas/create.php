<?php
include('../../config.php');
include('../../solo_administrador.php');
$nombre = $_POST['nombre'];

$sentencia = $pdo->prepare('INSERT INTO tb_areas (nombre) VALUES (:nombre)');

if ($sentencia->execute(['nombre' => $nombre])) {
	if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'El área se registró correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/areas/');
} else {
	if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo registrar el área';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/create.php');
}

if ($nombre === '') {
	if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Escriba el nombre del área';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/create.php');
    exit;
}