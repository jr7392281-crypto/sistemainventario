<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['sesion_email'])) {
    header('Location: ' . $URL . '/login/');
    exit;
}

require_once __DIR__ . '/bloquear_cambio_password.php';
exigir_cambio_password_si_corresponde($pdo, $URL);

$consulta_rol = $pdo->prepare(
    'SELECT r.rol
     FROM tb_usuarios AS u
     INNER JOIN tb_roles AS r ON r.id_rol = u.id_rol
     WHERE u.email = :email
     LIMIT 1'
);
$consulta_rol->execute([':email' => $_SESSION['sesion_email']]);
$rol_sesion = strtolower(trim((string) $consulta_rol->fetchColumn()));

if (!in_array($rol_sesion, ['administrador', 'super administrador'], true)) {
    $_SESSION['mensaje'] = 'No tienes permiso para acceder a esta sección.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
    exit;
}
