<?php
$consulta_auditoria = $pdo->prepare(
    'SELECT id_auditoria, id_usuario, usuario_nombre, usuario_rol,
            accion, modulo, id_registro, descripcion, fecha_registro, ip
     FROM tb_auditoria
     ORDER BY fecha_registro DESC, id_auditoria DESC'
);
$consulta_auditoria->execute();
$auditoria_datos = $consulta_auditoria->fetchAll(PDO::FETCH_ASSOC);
