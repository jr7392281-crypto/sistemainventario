<?php
$sql_areas = "SELECT * FROM tb_areas";
$sql_areas = $pdo->prepare($sql_areas);
$sql_areas->execute();
$areas_datos = $sql_areas->fetchAll(PDO::FETCH_ASSOC);