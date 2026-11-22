<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
include('../../solo_administrador.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$id_mantenimiento = (int) ($_POST['id_mantenimiento'] ?? 0);
$fecha = $_POST['fecha'] ?? '';
$tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? '';
$observacion = trim($_POST['observacion'] ?? '');

if ($id_mantenimiento < 1 || $fecha === '' || $tipo_mantenimiento === '' || $observacion === '') {
    $_SESSION['mensaje'] = 'Revisa los datos del mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

try {
    $pdo->beginTransaction();
    $consulta_anterior = $pdo->prepare(
        "SELECT id_mantenimiento FROM tb_mantenimientos
         WHERE id_mantenimiento = :id_mantenimiento FOR UPDATE"
    );
    $consulta_anterior->execute([':id_mantenimiento' => $id_mantenimiento]);
    $mantenimiento_anterior = $consulta_anterior->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento_anterior) {
        $pdo->rollBack();
        $_SESSION['mensaje'] = 'No se encontró el mantenimiento.';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/mantenimiento/');
        exit;
    }

    $actualizar = $pdo->prepare('UPDATE tb_mantenimientos SET
            fecha = :fecha,
            tipo_mantenimiento = :tipo_mantenimiento,
            observacion = :observacion
         WHERE id_mantenimiento = :id_mantenimiento');
    $actualizar->execute([
        ':fecha' => $fecha,
        ':tipo_mantenimiento' => $tipo_mantenimiento,
        ':observacion' => $observacion,
        ':id_mantenimiento' => $id_mantenimiento,
    ]);

    $pdo->commit();
    $_SESSION['mensaje'] = 'Se actualizaron los datos del mantenimiento correctamente.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al actualizar mantenimiento: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo actualizar el mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/update.php?id=' . $id_mantenimiento);
    exit;
}
