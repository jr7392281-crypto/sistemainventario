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
               p.modelo
        FROM tb_mantenimientos AS m
        INNER JOIN tb_usuarios AS u ON m.id_usuario = u.id_usuario
        LEFT JOIN tb_equipos AS e ON m.id_equipo = e.id_equipo
        LEFT JOIN tb_impresoras AS p ON m.id_impresora = p.id_impresora
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

$fecha = $mantenimiento_dato['fecha'];
$tipo_mantenimiento = $mantenimiento_dato['tipo_mantenimiento'];
$estado_mantenimiento = $mantenimiento_dato['estado'] ?? 'reportado';
$observacion = $mantenimiento_dato['observacion'];
$usuario = $mantenimiento_dato['usuario'];

$codigo_equipo = $mantenimiento_dato['codigo_equipo'];
$nombre_dispositivo = $mantenimiento_dato['nombre_dispositivo'];

$codigo_impresora = $mantenimiento_dato['codigo_impresora'];
$fabricante = $mantenimiento_dato['fabricante'];
$modelo = $mantenimiento_dato['modelo'];
?>