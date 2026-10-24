<?php
$id_area_get = $_GET['id'];

$sql_areas = "SELECT * FROM tb_areas WHERE id_area = '$id_area_get'";
$query_areas = $pdo->prepare($sql_areas);
$query_areas->execute();
$areas_datos = $query_areas->fetchAll(PDO::FETCH_ASSOC);

foreach ($areas_datos as $areas_dato) {
    $nombre_area = $areas_dato['nombre'];
}
?>
