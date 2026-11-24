<?php
include('../../config.php');
require_once __DIR__ . '/../../auditoria.php';
session_start();
registrar_auditoria($pdo, 'cerrar_sesion', 'autenticacion', null, 'Cerró sesión.');
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parametros = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametros['path'],
        $parametros['domain'],
        $parametros['secure'],
        $parametros['httponly']
    );
}

session_destroy();
header('Location: ' . $URL . '/login/');
exit;
