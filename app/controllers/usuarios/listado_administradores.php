<?php
$sql_administradores = "SELECT
            u.id_usuario,
            u.nombres,
            u.email,
            u.id_area,
            r.rol,
            a.nombre AS area
        FROM tb_usuarios u
        INNER JOIN tb_roles r ON u.id_rol = r.id_rol
        LEFT JOIN tb_areas a ON u.id_area = a.id_area
        WHERE LOWER(TRIM(r.rol)) = 'administrador'
        ORDER BY u.fecha_registro ASC, u.id_usuario ASC";

$query_administradores = $pdo->prepare($sql_administradores);
$query_administradores->execute();

$administradores_datos = $query_administradores->fetchAll(PDO::FETCH_ASSOC);
