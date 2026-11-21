<?php
$id_mantenimiento_get = (int) ($_GET['id'] ?? 0);
$estado_disponible = (bool) $pdo->query("SHOW COLUMNS FROM tb_mantenimientos LIKE 'estado'")->fetch(PDO::FETCH_ASSOC);

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
$mantenimiento = $query->fetch(PDO::FETCH_ASSOC);

if (!$mantenimiento) {
    $_SESSION['mensaje'] = 'No se encontró el mantenimiento';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}
