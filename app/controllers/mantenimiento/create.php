<?php
include('../../config.php');
include('../../layout/sesion.php');

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



if ($sentencia->execute()) {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Se registró el mantenimiento correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');

} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo registrar el mantenimiento';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/create.php');
}
