<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$nombre = trim((string) ($_POST['nombre'] ?? ''));
$descripcion = trim((string) ($_POST['descripcion'] ?? ''));

if ($nombre === '' || $descripcion === '') {
    $_SESSION['mensaje'] = 'Escribe el nombre y la descripción del área.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/');
    exit;
}

$sentencia = $pdo->prepare(
    'INSERT INTO tb_areas (nombre, descripcion)
     VALUES (:nombre, :descripcion)'
);

if ($sentencia->execute([
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
])) {
    $id_area = (int) $pdo->lastInsertId();
    registrar_auditoria($pdo, 'crear', 'areas', $id_area, 'Registró el área ' . $nombre . '.');
    $_SESSION['mensaje'] = 'El área se registró correctamente.';
    $_SESSION['icono'] = 'success';
} else {
    $_SESSION['mensaje'] = 'No se pudo registrar el área.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/areas/');
exit;
