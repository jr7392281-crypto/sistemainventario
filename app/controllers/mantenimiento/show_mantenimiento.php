<?php
$id_mantenimiento_get = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_mantenimiento_get || $id_mantenimiento_get < 1) {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$sql = "SELECT m.*,
               u.nombres AS usuario,
               e.codigo AS codigo_equipo,
               e.nombre_dispositivo,
               p.codigo AS codigo_impresora,
               p.fabricante,
               p.modelo,
               t.nombres AS nombre_tecnico
        FROM tb_mantenimientos AS m
        INNER JOIN tb_usuarios AS u ON m.id_usuario = u.id_usuario
        LEFT JOIN tb_equipos AS e ON m.id_equipo = e.id_equipo
        LEFT JOIN tb_impresoras AS p ON m.id_impresora = p.id_impresora
        LEFT JOIN tb_usuarios AS t ON m.id_tecnico_asignado = t.id_usuario
        WHERE m.id_mantenimiento = :id";

$query = $pdo->prepare($sql);
$query->bindValue(':id', $id_mantenimiento_get, PDO::PARAM_INT);
$query->execute();

$mantenimiento_dato = $query->fetch(PDO::FETCH_ASSOC);

if (!$mantenimiento_dato) {
    $_SESSION['mensaje'] = 'No se encontró el mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$rol_actual = strtolower(trim((string) ($rol_sesion ?? '')));
$id_usuario_actual = (int) ($id_usuario_sesion ?? 0);
$puede_ver = in_array($rol_actual, ['administrador', 'super administrador'], true);

if ($rol_actual === 'personal') {
    $puede_ver = (int) $mantenimiento_dato['id_usuario'] === $id_usuario_actual;
} elseif ($rol_actual === 'soporte tecnico') {
    $puede_ver = in_array($mantenimiento_dato['estado'] ?? '', ['enviado', 'rechazado'], true)
        || (($mantenimiento_dato['estado'] ?? '') === 'aceptado' && empty($mantenimiento_dato['id_tecnico_asignado']))
        || (int) ($mantenimiento_dato['id_tecnico_asignado'] ?? 0) === $id_usuario_actual;
}

if (!$puede_ver) {
    $_SESSION['mensaje'] = 'No tienes permiso para ver este mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$consulta_componentes_usados = $pdo->prepare(
    'SELECT c.codigo, c.nombre, ms.cantidad, ms.observacion, ms.fecha_registro,
            u.nombres AS tecnico
     FROM tb_movimientos_stock AS ms
     INNER JOIN tb_componentes AS c ON c.id_componente = ms.id_componente
     INNER JOIN tb_usuarios AS u ON u.id_usuario = ms.id_usuario
     WHERE ms.id_mantenimiento = :id_mantenimiento
     ORDER BY ms.fecha_registro DESC, ms.id_movimiento DESC'
);
$consulta_componentes_usados->execute([':id_mantenimiento' => $id_mantenimiento_get]);
$componentes_usados = $consulta_componentes_usados->fetchAll(PDO::FETCH_ASSOC);

$fecha = $mantenimiento_dato['fecha'];
$tipo_mantenimiento = $mantenimiento_dato['tipo_mantenimiento'];
$estado_mantenimiento = $mantenimiento_dato['estado'] ?? 'reportado';
$observacion = $mantenimiento_dato['observacion'];
$usuario = $mantenimiento_dato['usuario'];
$tecnico_asignado = $mantenimiento_dato['id_tecnico_asignado'] ?? null;
$nombre_tecnico = $mantenimiento_dato['nombre_tecnico'] ?? '';
$solucion_mantenimiento = $mantenimiento_dato['solucion'] ?? '';

$codigo_equipo = $mantenimiento_dato['codigo_equipo'];
$nombre_dispositivo = $mantenimiento_dato['nombre_dispositivo'];

$codigo_impresora = $mantenimiento_dato['codigo_impresora'];
$fabricante = $mantenimiento_dato['fabricante'];
$modelo = $mantenimiento_dato['modelo'];
?>
