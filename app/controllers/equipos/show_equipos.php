<?php

$id_equipo_get = $_GET['id'];

$sql_equipos = "SELECT *,
                       a.nombre as nombre
                FROM tb_equipos as e
                INNER JOIN tb_areas as a
                ON e.id_area = a.id_area
                WHERE id_equipo = '$id_equipo_get'";

$query_equipos = $pdo->prepare($sql_equipos);
$query_equipos->execute();
$equipos_datos = $query_equipos->fetchAll(PDO::FETCH_ASSOC);

foreach ($equipos_datos as $equipos_dato){

    $codigo = $equipos_dato['codigo'];
    $nombre_dispositivo = $equipos_dato['nombre_dispositivo'];
    $procesador = $equipos_dato['procesador'];
    $ram = $equipos_dato['ram'];
    $tarjeta_grafica = $equipos_dato['tarjeta_grafica'];
    $sistema_operativo = $equipos_dato['sistema_operativo'];
    $almacenamiento = $equipos_dato['almacenamiento'];
    $monitor = $equipos_dato['monitor'];
    $ip = $equipos_dato['ip'];
    $mouse = $equipos_dato['mouse'];
    $teclado = $equipos_dato['teclado'];
    $id_area = $equipos_dato['id_area'];
    $nombre_area = $equipos_dato['nombre'];
    $estado = $equipos_dato['estado'];
    $observacion = $equipos_dato['observacion'];
    $fecha_registro = $equipos_dato['fecha_registro'];
    $fecha_actualizacion = $equipos_dato['fecha_actualizacion'];
}

?>

