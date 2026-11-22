<?php
$rol_actual = strtolower(trim((string) ($rol_sesion ?? '')));
$id_usuario_actual = (int) ($id_usuario_sesion ?? 0);

if (!in_array($rol_actual, ['administrador', 'super administrador', 'personal', 'soporte tecnico'], true)) {
    $_SESSION['mensaje'] = 'Tu rol no tiene acceso a los mantenimientos.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
    exit;
}

$sql_mantenimientos = "SELECT
    m.id_mantenimiento,
    m.fecha,
    m.tipo_mantenimiento,
    m.id_tecnico_asignado,
    m.estado,
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
";

if ($rol_actual === 'personal') {
    $sql_mantenimientos .= ' WHERE m.id_usuario = :id_usuario';
} elseif ($rol_actual === 'soporte tecnico') {
    $sql_mantenimientos .= " WHERE m.estado IN ('enviado', 'rechazado')
        OR (m.estado = 'aceptado' AND m.id_tecnico_asignado IS NULL)
        OR m.id_tecnico_asignado = :id_tecnico";
}

$sql_mantenimientos .= ' ORDER BY m.fecha DESC, m.id_mantenimiento DESC';

$query_mantenimientos = $pdo->prepare($sql_mantenimientos);
$parametros = [];
if ($rol_actual === 'personal') {
    $parametros[':id_usuario'] = $id_usuario_actual;
} elseif ($rol_actual === 'soporte tecnico') {
    $parametros[':id_tecnico'] = $id_usuario_actual;
}
$query_mantenimientos->execute($parametros);
$mantenimientos_datos = $query_mantenimientos->fetchAll(PDO::FETCH_ASSOC);
?>
