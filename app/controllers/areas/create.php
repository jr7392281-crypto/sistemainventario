<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';
$nombre = $_POST['nombre'];

if (trim((string) $nombre) === '') {
    $_SESSION['mensaje'] = 'Escriba el nombre del área';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/create.php');
    exit;
}

$sentencia = $pdo->prepare('INSERT INTO tb_areas (nombre) VALUES (:nombre)');

if ($sentencia->execute(['nombre' => $nombre])) {
    $id_area = (int) $pdo->lastInsertId();
    registrar_auditoria($pdo, 'crear', 'areas', $id_area, 'Registró el área ' . $nombre . '.');
    $_SESSION['mensaje'] = 'El área se registró correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/areas/');
} else {
	if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo registrar el área';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/create.php');
}
