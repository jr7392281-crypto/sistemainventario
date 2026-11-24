<?php
include('../../config.php');
require_once __DIR__ . '/../../auditoria.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/login/');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password_user = $_POST['password_user'] ?? '';

$sentencia = $pdo->prepare(
    'SELECT email, password_user, email_verificado, debe_cambiar_password
     FROM tb_usuarios
     WHERE email = :email
     LIMIT 1'
);
$sentencia->execute(['email' => $email]);
$usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password_user, $usuario['password_user'])) {
    if ((int) $usuario['email_verificado'] !== 1) {
        $_SESSION['mensaje'] = 'Confirma tu correo desde el enlace enviado. Si no lo recibiste o venció, contacta al administrador.';
        $_SESSION['icono'] = 'warning';
        header('Location: ' . $URL . '/login/');
        exit;
    }

    session_regenerate_id(true);
    unset($_SESSION['mensaje']);
    unset($_SESSION['icono']);
    $_SESSION['sesion_email'] = $usuario['email'];
    registrar_auditoria($pdo, 'inicio_sesion', 'autenticacion', null, 'Inició sesión correctamente.');

    if ((int) $usuario['debe_cambiar_password'] === 1) {
        header('Location: ' . $URL . '/login/nueva_contrasena_temporal.php');
        exit;
    }

    header('Location: ' . $URL . '/index.php');
    exit;
}

$_SESSION['mensaje'] = 'Correo o contraseña incorrectos.';
$_SESSION['icono'] = 'error';
header('Location: ' . $URL . '/login/');
exit;
