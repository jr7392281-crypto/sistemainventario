
<?php

include('../../config.php');
include('../../solo_administrador.php');

$codigo = $_POST['codigo'];
$nombre_dispositivo = $_POST['nombre_dispositivo'];
$procesador = $_POST['procesador'];
$ram = $_POST['ram'];
$tarjeta_grafica = $_POST['tarjeta_grafica'];
$sistema_operativo = $_POST['sistema_operativo'];
$almacenamiento = $_POST['almacenamiento'];
$monitor = $_POST['monitor'];
$ip = $_POST['ip'];
$mouse = $_POST['mouse'];
$teclado = $_POST['teclado'];
$id_area = $_POST['id_area'];
$estado = $_POST['estado'];
$observacion = $_POST['observacion'];

$sentencia = $pdo->prepare("INSERT INTO tb_equipos
       (codigo,
        nombre_dispositivo,
        procesador,
        ram,
        tarjeta_grafica,
        sistema_operativo,
        almacenamiento,
        monitor,
        ip,
        mouse,
        teclado,
        id_area,
        estado,
        observacion,
        fecha_registro,
        fecha_actualizacion)
VALUES (:codigo,
        :nombre_dispositivo,
        :procesador,
        :ram,
        :tarjeta_grafica,
        :sistema_operativo,
        :almacenamiento,
        :monitor,
        :ip,
        :mouse,
        :teclado,
        :id_area,
        :estado,
        :observacion,
        :fecha_registro,
        :fecha_actualizacion)");

$sentencia->bindParam('codigo', $codigo);
$sentencia->bindParam('nombre_dispositivo', $nombre_dispositivo);
$sentencia->bindParam('procesador', $procesador);
$sentencia->bindParam('ram', $ram);
$sentencia->bindParam('tarjeta_grafica', $tarjeta_grafica);
$sentencia->bindParam('sistema_operativo', $sistema_operativo);
$sentencia->bindParam('almacenamiento', $almacenamiento);
$sentencia->bindParam('monitor', $monitor);
$sentencia->bindParam('ip', $ip);
$sentencia->bindParam('mouse', $mouse);
$sentencia->bindParam('teclado', $teclado);
$sentencia->bindParam('id_area', $id_area);
$sentencia->bindParam('estado', $estado);
$sentencia->bindParam('observacion', $observacion);
$sentencia->bindParam('fecha_registro', $fechaHora);
$sentencia->bindParam('fecha_actualizacion', $fechaHora);

if ($sentencia->execute()) {

    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

    $_SESSION['mensaje'] = "Se registro el equipo de la manera correcta";
    $_SESSION['icono'] = "success";

    header('Location: ' . $URL . '/equipos/');

} else {

    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

    $_SESSION['mensaje'] = "Error no se pudo registrar el equipo en la base de datos";
    $_SESSION['icono'] = "error";

    header('Location: ' . $URL . '/equipos/create.php');
}
