<?php

$id_impresora_get = $_GET['id'];

$sql_impresoras = "SELECT *,
                          a.nombre AS nombre_area
                   FROM tb_impresoras AS i
                   INNER JOIN tb_areas AS a
                   ON i.id_area = a.id_area
                   WHERE id_impresora = '$id_impresora_get'";

$query_impresoras = $pdo->prepare($sql_impresoras);
$query_impresoras->execute();
$impresoras_datos = $query_impresoras->fetchAll(PDO::FETCH_ASSOC);


foreach ($impresoras_datos as $impresora_dato) {
    $impresora = $impresora_dato;
    $codigo = $impresora_dato['codigo'];
    $fabricante = $impresora_dato['fabricante'];
    $modelo = $impresora_dato['modelo'];
    $tipo_equipo = $impresora_dato['tipo_equipo'];
    $tipo_impresion = $impresora_dato['tipo_impresion'];
    $id_area = $impresora_dato['id_area'];
    $nombre_area = $impresora_dato['nombre_area'];
    $estado = $impresora_dato['estado'];
    $observacion = $impresora_dato['observacion'];
    $fecha_registro = $impresora_dato['fecha_registro'];
    $fecha_actualizacion = $impresora_dato['fecha_actualizacion'];
}
?>