<?php
include('../../config.php');
include('../../solo_administrador.php');

$id_impresora = $_POST['id_impresora'];
$fabricante = $_POST['fabricante'];
$modelo = $_POST['modelo'];
$tipo_equipo = $_POST['tipo_equipo'];
$tipo_impresion = $_POST['tipo_impresion'];
$id_area = $_POST['id_area'];
$estado = $_POST['estado'];
$observacion = $_POST['observacion'];

$sentencia = $pdo->prepare("UPDATE tb_impresoras SET
    fabricante = :fabricante,
    modelo = :modelo,
    tipo_equipo = :tipo_equipo,
    tipo_impresion = :tipo_impresion,
    id_area = :id_area,
    estado = :estado,
    observacion = :observacion,
    fecha_actualizacion = :fecha_actualizacion
    WHERE id_impresora = :id_impresora");

$sentencia->bindParam(':fabricante', $fabricante);
$sentencia->bindParam(':modelo', $modelo);
$sentencia->bindParam(':tipo_equipo', $tipo_equipo);
$sentencia->bindParam(':tipo_impresion', $tipo_impresion);
$sentencia->bindParam(':id_area', $id_area);
$sentencia->bindParam(':estado', $estado);
$sentencia->bindParam(':observacion', $observacion);
$sentencia->bindParam(':fecha_actualizacion', $fechaHora);
$sentencia->bindParam(':id_impresora', $id_impresora);
$sentencia->execute();

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$_SESSION['mensaje'] = 'Se actualizó la impresora correctamente';
$_SESSION['icono'] = 'success';
header('Location: ' . $URL . '/impresoras/');
?>
