<?php
include('../../config.php');
include('../../solo_administrador.php');

$codigo = $_POST['codigo'];
$fabricante = $_POST['fabricante'];
$modelo = $_POST['modelo'];
$tipo_impresora = $_POST['tipo_impresora'];
$tipo_impresion = $_POST['tipo_impresion'];
$id_area = $_POST['id_area'];
$estado = $_POST['estado'];
$observacion = $_POST['observacion'];

$sentencia = $pdo->prepare(
    "INSERT INTO tb_impresoras
        (codigo, fabricante, modelo, tipo_impresora, tipo_impresion,
         id_area, estado, observacion)
     VALUES
        (:codigo, :fabricante, :modelo, :tipo_impresora, :tipo_impresion,
         :id_area, :estado, :observacion)"
);

$sentencia->bindParam(':codigo', $codigo);
$sentencia->bindParam(':fabricante', $fabricante);
$sentencia->bindParam(':modelo', $modelo);
$sentencia->bindParam(':tipo_impresora', $tipo_impresora);
$sentencia->bindParam(':tipo_impresion', $tipo_impresion);
$sentencia->bindParam(':id_area', $id_area);
$sentencia->bindParam(':estado', $estado);
$sentencia->bindParam(':observacion', $observacion);

if ($sentencia->execute()) {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'Se registró la impresora correctamente';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/impresoras/');
} else {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['mensaje'] = 'No se pudo registrar la impresora';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/impresoras/create.php');
}
