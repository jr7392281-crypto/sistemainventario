<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Inventario y Mantenimiento de Equipos</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../public/templates/templates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="../public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css?v=3.2.0">
    <!-- Librería SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <?php
        session_start();
        if (isset($_SESSION['mensaje'])) {
            $respuesta = $_SESSION['mensaje'];
            $icono = $_SESSION['icono'] ?? 'error';
            unset($_SESSION['mensaje']);
            unset($_SESSION['icono']);
            ?>
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: <?php echo json_encode($icono); ?>,
                    title: <?php echo json_encode($respuesta); ?>,
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
            <?php
        }
        ?>
        <center>
            <img src="../public/images/logo-muni.png" alt="" width="200px">
        </center>
        <br>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Ingrese sus Datos</p>

            <form action="../app/controllers/login/ingreso.php" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="password_user" name="password_user" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text text-muted" style="cursor:pointer;background:#fff;"
                            onclick="togglePasswordLogin()" aria-label="Mostrar u ocultar contraseña">
                            <span id="icono_password" class="fas fa-eye"></span>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </div>

                </div>
            </form>
            <p class="mt-3 mb-0 text-center">
                <a href="olvidar.php">¿Olvidaste tu contraseña?</a>
            </p>

        </div>

    </div>
    </div>


    <script>
        function togglePasswordLogin() {
            const password = document.getElementById('password_user');
            const icono = document.getElementById('icono_password');
            const mostrar = password.type === 'password';

            password.type = mostrar ? 'text' : 'password';
            icono.classList.toggle('fa-eye', !mostrar);
            icono.classList.toggle('fa-eye-slash', mostrar);
        }
    </script>
</body>

</html>