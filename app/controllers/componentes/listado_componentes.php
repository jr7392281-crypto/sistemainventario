<?php

$sqlComponentes = 'SELECT codigo, nombre, tipo_componente, aplica_a, marca,
                          especificacion, stock_actual, stock_minimo
                   FROM tb_componentes
                   WHERE estado = 1
                   ORDER BY tipo_componente, nombre';

$queryComponentes = $pdo->prepare($sqlComponentes);
$queryComponentes->execute();
$componentes_datos = $queryComponentes->fetchAll(PDO::FETCH_ASSOC);
