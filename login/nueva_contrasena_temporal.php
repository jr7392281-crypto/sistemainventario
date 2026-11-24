<?php
include('../app/config.php');
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

if ((int) $debe_cambiar !== 1) {
    header('Location: ' . $URL . '/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear contraseña - Sistema de Inventario</title>
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/css/sistema.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box" style="width: min(460px, 94vw);">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h1 class="h3 mb-0">Nueva contraseña</h1>
            </div>
            <div class="card-body">
                <p class="text-muted">Tu clave temporal fue validada. Crea una contraseña personal para continuar.</p>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    La clave temporal ya fue verificada. No necesitas ingresarla nuevamente.
                </div>

                <?php if (isset($_SESSION['mensaje'])) { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars((string) $_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['icono']); ?>
                <?php } ?>

                <form action="../app/controllers/login/guardar_contrasena_temporal.php" method="POST">
                    <div class="form-group">
                        <label for="password_user">Nueva contraseña</label>
                        <input id="password_user" type="password" name="password_user" class="form-control"
                            minlength="8" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_repeat">Confirmar nueva contraseña</label>
                        <input id="password_repeat" type="password" name="password_repeat" class="form-control"
                            minlength="8" autocomplete="new-password" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Usa al menos 8 caracteres. Al guardar, entrarás al sistema con tu nueva contraseña.
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Guardar nueva contraseña
                    </button>
                </form>
                <a href="../app/controllers/login/cerrar_sesion.php" class="btn btn-outline-secondary btn-block mt-2">Volver al login</a>
            </div>
        </div>
    </div>
</body>
</html>
