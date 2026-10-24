<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
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
                <p class="login-box-msg">Restablecer contraseña</p>
                <p>Ingresa tu correo. Si está registrado, recibirás un enlace para crear una contraseña nueva.</p>

                <form action="../app/controllers/login/solicitar_restablecimiento.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control"
                            placeholder="Correo electrónico" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar enlace</button>
                </form>

                <p class="mt-3 mb-0 text-center"><a href="index.php">Volver al inicio de sesión</a></p>
            </div>
        </div>
    </div>
</body>
</html>
