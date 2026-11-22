<?php

$sqlComponentes = 'SELECT id_componente, codigo, nombre, stock_actual
                   FROM tb_componentes
                   WHERE estado = 1
                   ORDER BY nombre';
$queryComponentes = $pdo->prepare($sqlComponentes);
$queryComponentes->execute();
$componentes_datos = $queryComponentes->fetchAll(PDO::FETCH_ASSOC);

$mantenimientos_datos = [];
$id_mantenimiento_preseleccionado = filter_input(INPUT_GET, 'id_mantenimiento', FILTER_VALIDATE_INT);

if (strtolower(trim((string) ($rol_sesion ?? ''))) === 'soporte tecnico') {
    $queryMantenimientos = $pdo->prepare(
        "SELECT id_mantenimiento, fecha, tipo_mantenimiento
         FROM tb_mantenimientos
         WHERE estado = 'aceptado'
           AND id_tecnico_asignado = :id_tecnico
         ORDER BY id_mantenimiento DESC
         LIMIT 100"
    );
    $queryMantenimientos->execute([':id_tecnico' => (int) $id_usuario_sesion]);
    $mantenimientos_datos = $queryMantenimientos->fetchAll(PDO::FETCH_ASSOC);
}

$sqlMovimientos = 'SELECT m.id_movimiento, m.tipo, m.cantidad, m.observacion,
                          m.fecha_registro, c.codigo,
                          c.nombre AS componente, u.nombres AS usuario,
                          m.id_mantenimiento
                   FROM tb_movimientos_stock AS m
                   INNER JOIN tb_componentes AS c ON c.id_componente = m.id_componente
                   INNER JOIN tb_usuarios AS u ON u.id_usuario = m.id_usuario
                   ORDER BY m.fecha_registro DESC, m.id_movimiento DESC
                   LIMIT 100';
$queryMovimientos = $pdo->prepare($sqlMovimientos);
$queryMovimientos->execute();
$movimientos_datos = $queryMovimientos->fetchAll(PDO::FETCH_ASSOC);
