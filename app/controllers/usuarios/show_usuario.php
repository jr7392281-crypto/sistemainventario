<?php
$id_usuario_get = $_GET['id'];

$sql_usuarios = "SELECT us.id_usuario,
                        us.nombres,
                        us.email,
                        rol.rol,
                        COALESCE(a.nombre, 'Sin área') AS area
                 FROM tb_usuarios AS us
                 INNER JOIN tb_roles AS rol ON us.id_rol = rol.id_rol
                 LEFT JOIN tb_areas AS a ON us.id_area = a.id_area
                 WHERE us.id_usuario = :id_usuario";

$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute([':id_usuario' => $id_usuario_get]);
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios_datos as $usuarios_dato) {
    $nombres = $usuarios_dato['nombres'];
    $email = $usuarios_dato['email'];
    $rol = $usuarios_dato['rol'];
    $area = $usuarios_dato['area'];
}
?>