<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['sesion_email'])) {
    header('Location: ' . $URL . '/login/');
    exit;
}

$consulta_rol = $pdo->prepare(
    'SELECT r.rol
     FROM tb_usuarios AS u
     INNER JOIN tb_roles AS r ON r.id_rol = u.id_rol
     WHERE u.email = :email
     LIMIT 1'
);
$consulta_rol->execute([':email' => $_SESSION['sesion_email']]);
$rol_sesion = $consulta_rol->fetchColumn();

if (!in_array(strtolower(trim((string) $rol_sesion)), ['administrador', 'super administrador'], true)) {
    $_SESSION['mensaje'] = 'No tienes permiso para acceder a esta sección.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
    exit;
}