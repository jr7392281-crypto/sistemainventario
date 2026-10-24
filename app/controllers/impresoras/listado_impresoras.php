<?php
$sql = "SELECT i.id_impresora, i.codigo, i.fabricante, i.modelo,
       i.tipo_equipo, i.tipo_impresion, a.nombre AS area,
       i.estado, i.observacion
FROM tb_impresoras AS i
INNER JOIN tb_areas AS a ON a.id_area = i.id_area
ORDER BY i.codigo;";


$query_impresoras = $pdo->prepare($sql);
$query_impresoras->execute();
$impresoras_datos = $query_impresoras->fetchAll(PDO::FETCH_ASSOC);