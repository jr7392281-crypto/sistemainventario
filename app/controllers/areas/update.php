<?php
include('../../config.php');
include('../../solo_administrador_o_super_administrador.php');
require_once __DIR__ . '/../../auditoria.php';

$id_area = (int) ($_POST['id_area'] ?? 0);
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$descripcion = trim((string) ($_POST['descripcion'] ?? ''));

if ($id_area < 1 || $nombre === '' || $descripcion === '') {
    $_SESSION['mensaje'] = 'Escribe el nombre y la descripción del área.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/areas/');
    exit;
}

$sentencia = $pdo->prepare(
    'UPDATE tb_areas
     SET nombre = :nombre,
         descripcion = :descripcion,
         fecha_actualizacion = :fecha_actualizacion
     WHERE id_area = :id_area'
);

if ($sentencia->execute([
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':fecha_actualizacion' => $fechaHora,
    ':id_area' => $id_area,
])) {
    registrar_auditoria($pdo, 'editar', 'areas', $id_area, 'Actualizó el área ' . $nombre . '.');
    $_SESSION['mensaje'] = 'Se actualizó el área correctamente.';
    $_SESSION['icono'] = 'success';
} else {
    $_SESSION['mensaje'] = 'No se pudo actualizar el área.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/areas/');
exit;
