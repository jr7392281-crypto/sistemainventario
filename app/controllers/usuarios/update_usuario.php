<?php
$id_usuario_get = $_GET['id'];

$sql_usuarios = "SELECT us.id_usuario, us.nombres, us.email, us.id_area, rol.rol
                 FROM tb_usuarios AS us
                 INNER JOIN tb_roles AS rol ON us.id_rol = rol.id_rol
                 WHERE us.id_usuario = :id_usuario";
$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->execute([':id_usuario' => $id_usuario_get]);
$usuarios_dato = $query_usuarios->fetch(PDO::FETCH_ASSOC);

if ($usuarios_dato) {
    $nombres = $usuarios_dato['nombres'];
    $email = $usuarios_dato['email'];
    $rol = $usuarios_dato['rol'];
    $id_area_actual = $usuarios_dato['id_area'];
} else {
    $nombres = '';
    $email = '';
    $rol = '';
    $id_area_actual = null;
}
?>
