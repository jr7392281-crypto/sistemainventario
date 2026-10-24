<?php
$sql_usuarios = "SELECT us.id_usuario,
       us.nombres,
       us.email,
       rol.rol,
       COALESCE(a.nombre, 'Sin área') AS area
FROM tb_usuarios us
INNER JOIN tb_roles rol ON us.id_rol = rol.id_rol
LEFT JOIN tb_areas a ON us.id_area = a.id_area";
$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute();
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
?>