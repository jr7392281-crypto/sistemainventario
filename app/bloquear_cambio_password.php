<?php
function exigir_cambio_password_si_corresponde(PDO $pdo, string $URL): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $email = trim((string) ($_SESSION['sesion_email'] ?? ''));
    if ($email === '') {
        header('Location: ' . $URL . '/login/');
        exit;
    }

    $consulta = $pdo->prepare(
        'SELECT debe_cambiar_password FROM tb_usuarios WHERE email = :email LIMIT 1'
    );
    $consulta->execute([':email' => $email]);
    $debe_cambiar = $consulta->fetchColumn();

    if ($debe_cambiar === false) {
        session_unset();
        session_destroy();
        header('Location: ' . $URL . '/login/');
        exit;
    }

    if ((int) $debe_cambiar === 1) {
        header('Location: ' . $URL . '/login/nueva_contrasena_temporal.php');
        exit;
    }
}
