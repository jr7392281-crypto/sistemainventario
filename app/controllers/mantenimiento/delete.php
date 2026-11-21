<?php
include('../../config.php');
include('../../solo_administrador.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$id_mantenimiento = filter_input(INPUT_POST, 'id_mantenimiento', FILTER_VALIDATE_INT);

if (!$id_mantenimiento || $id_mantenimiento < 1) {
    $_SESSION['mensaje'] = 'El mantenimiento indicado no es válido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

try {
    $sentencia = $pdo->prepare(
        'DELETE FROM tb_mantenimientos
         WHERE id_mantenimiento = :id_mantenimiento'
    );
    $sentencia->execute([':id_mantenimiento' => $id_mantenimiento]);

    $_SESSION['mensaje'] = $sentencia->rowCount() > 0
        ? 'Se eliminó el mantenimiento correctamente.'
        : 'No se encontró el mantenimiento.';
    $_SESSION['icono'] = $sentencia->rowCount() > 0 ? 'success' : 'error';
} catch (PDOException $e) {
    error_log('Error al eliminar mantenimiento: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo eliminar. Verifica si tiene movimientos de stock asociados.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $URL . '/mantenimiento/');
exit;