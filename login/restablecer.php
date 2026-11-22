<?php
include('../app/config.php');
session_start();

$token = $_GET['token'] ?? '';
$token_valido = false;

if (is_string($token) && preg_match('/^[a-f0-9]{64}$/', $token)) {
    $consulta = $pdo->prepare(
        'SELECT id_reset
         FROM tb_password_resets
         WHERE token_hash = :token_hash
           AND fecha_uso IS NULL
           AND fecha_expiracion > NOW()
         LIMIT 1'
    );
    $consulta->execute([':token_hash' => hash('sha256', $token)]);
    $token_valido = (bool) $consulta->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear nueva contraseña</title>
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../public/css/sistema.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <?php if (isset($_SESSION['mensaje'])) { ?>
            <script>
                Swal.fire({
                    icon: <?php echo json_encode($_SESSION['icono'] ?? 'info'); ?>,
                    title: <?php echo json_encode($_SESSION['mensaje']); ?>,
                    confirmButtonText: 'Aceptar'
                });
            </script>
            <?php
            unset($_SESSION['mensaje'], $_SESSION['icono']);
        } ?>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Crear nueva contraseña</p>

                <?php if ($token_valido) { ?>
                    <form action="../app/controllers/login/cambiar_contrasena.php" method="post">
                        <input type="hidden" name="token"
                            value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="input-group mb-3">
                            <input type="password" name="password_user" class="form-control"
                                placeholder="Nueva contraseña" minlength="8" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password_repeat" class="form-control"
                                placeholder="Repetir contraseña" minlength="8" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Guardar contraseña</button>
                    </form>
                <?php } else { ?>
                    <p>El enlace no es válido o ya venció. Solicita uno nuevo.</p>
                    <a href="olvidar.php" class="btn btn-primary btn-block">Solicitar otro enlace</a>
                <?php } ?>

                <p class="mt-3 mb-0 text-center"><a href="index.php">Volver al inicio de sesión</a></p>
            </div>
        </div>
    </div>
</body>
</html>
