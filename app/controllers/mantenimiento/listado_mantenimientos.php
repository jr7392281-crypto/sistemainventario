<?php
$sql_mantenimientos = "SELECT
    m.id_mantenimiento,
    m.fecha,
    m.tipo_mantenimiento,
    m.observacion,
    u.nombres AS usuario,
    e.codigo AS codigo_equipo,
    e.nombre_dispositivo,
    p.codigo AS codigo_impresora,
    p.fabricante,
    p.modelo
FROM tb_mantenimientos m
INNER JOIN tb_usuarios u
    ON m.id_usuario = u.id_usuario
LEFT JOIN tb_equipos e
    ON m.id_equipo = e.id_equipo
LEFT JOIN tb_impresoras p
    ON m.id_impresora = p.id_impresora
ORDER BY m.fecha DESC, m.id_mantenimiento DESC";

$query_mantenimientos = $pdo->prepare($sql_mantenimientos);
$query_mantenimientos->execute();
$mantenimientos_datos = $query_mantenimientos->fetchAll(PDO::FETCH_ASSOC);
?>