<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once '../../notificaciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}

$id_equipo = ($_POST['id_equipo'] ?? '') === '' ? null : (int) $_POST['id_equipo'];
$id_impresora = ($_POST['id_impresora'] ?? '') === '' ? null : (int) $_POST['id_impresora'];
$id_usuario = $id_usuario_sesion;
$fecha = $_POST['fecha'] ?? '';
$tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? '';
$observacion = trim($_POST['observacion'] ?? '');

if (($id_equipo === null) === ($id_impresora === null)) {
    $_SESSION['mensaje'] = 'Selecciona un equipo o una impresora, pero no ambos.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}

$sentencia = $pdo->prepare(
    "INSERT INTO tb_mantenimientos
        (id_equipo, id_impresora, id_usuario, fecha, tipo_mantenimiento, observacion)
     VALUES
        (:id_equipo, :id_impresora, :id_usuario, :fecha, :tipo_mantenimiento, :observacion)"
);

$sentencia->bindValue(':id_equipo', $id_equipo, $id_equipo === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
$sentencia->bindValue(':id_impresora', $id_impresora, $id_impresora === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
$sentencia->bindParam(':id_usuario', $id_usuario);
$sentencia->bindParam(':fecha', $fecha);
$sentencia->bindParam(':tipo_mantenimiento', $tipo_mantenimiento);
$sentencia->bindParam(':observacion', $observacion);



try {
    $pdo->beginTransaction();
    $sentencia->execute();
    $id_mantenimiento = (int) $pdo->lastInsertId();

    $notificaciones_activas = true;
    try {
        notificarRoles(
            $pdo,
            ['administrador', 'encargado de inventario'],
            (int) $id_usuario,
            $id_mantenimiento,
            'nuevo_mantenimiento',
            'Nuevo reporte de mantenimiento',
            $nombres_sesion . ' registró un nuevo reporte de mantenimiento.'
        );
    } catch (PDOException $error_notificaciones) {
        $notificaciones_activas = false;
        error_log('La notificación del nuevo mantenimiento no está disponible: ' . $error_notificaciones->getMessage());
    }

    $pdo->commit();
    $_SESSION['mensaje'] = $notificaciones_activas
        ? 'Se registró el mantenimiento y se notificó al equipo responsable.'
        : 'Se registró el mantenimiento. Aplica la migración de notificaciones para activar los avisos.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar mantenimiento y notificaciones: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo registrar el mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/create.php');
    exit;
}
