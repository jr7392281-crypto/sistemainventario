<?php
$id_mantenimiento_get = $_GET['id'];

$sql_mantenimiento = "SELECT m.*,
                             u.nombres AS usuario,
                             e.codigo AS codigo_equipo,
                             e.nombre_dispositivo,
                             p.codigo AS codigo_impresora,
                             p.fabricante,
                             p.modelo
                      FROM tb_mantenimientos AS m
                      INNER JOIN tb_usuarios AS u
                          ON m.id_usuario = u.id_usuario
                      LEFT JOIN tb_equipos AS e
                          ON m.id_equipo = e.id_equipo
                      LEFT JOIN tb_impresoras AS p
                          ON m.id_impresora = p.id_impresora
                      WHERE m.id_mantenimiento = '$id_mantenimiento_get'";

$query_mantenimiento = $pdo->prepare($sql_mantenimiento);
$query_mantenimiento->execute();

$mantenimientos_datos = $query_mantenimiento->fetchAll(PDO::FETCH_ASSOC);

foreach ($mantenimientos_datos as $mantenimiento_dato) {

    $fecha = $mantenimiento_dato['fecha'];
    $tipo_mantenimiento = $mantenimiento_dato['tipo_mantenimiento'];
    $observacion = $mantenimiento_dato['observacion'];
    $usuario = $mantenimiento_dato['usuario'];

    $codigo_equipo = $mantenimiento_dato['codigo_equipo'];
    $nombre_dispositivo = $mantenimiento_dato['nombre_dispositivo'];

    $codigo_impresora = $mantenimiento_dato['codigo_impresora'];
    $fabricante = $mantenimiento_dato['fabricante'];
    $modelo = $mantenimiento_dato['modelo'];
}
?>