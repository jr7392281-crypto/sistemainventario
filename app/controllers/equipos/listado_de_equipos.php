<?php

$sql_equipos = "SELECT *, ar.nombre as area
                FROM tb_equipos as e
                INNER JOIN tb_areas as ar ON e.id_area = ar.id_area";

$query_equipos = $pdo->prepare($sql_equipos);
$query_equipos->execute();
$equipos_datos = $query_equipos->fetchAll(PDO::FETCH_ASSOC);
?>
