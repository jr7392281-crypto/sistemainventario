<?php
$sqlComponentes = 'SELECT id_componente
                   FROM tb_componentes';
$queryComponentes = $pdo->prepare($sqlComponentes);
$queryComponentes->execute();
$componentes_datos = $queryComponentes->fetchAll(PDO::FETCH_ASSOC);
