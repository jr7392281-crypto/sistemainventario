<?php
include('../../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/login/olvidar.php');
    exit;
}

$email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje'] = 'Ingresa un correo válido.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/login/olvidar.php');
    exit;
}

$respuesta = 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.';
$icono = 'success';

try {
    $consulta = $pdo->prepare(
        'SELECT id_usuario, nombres, email
         FROM tb_usuarios
         WHERE email = :email
         LIMIT 1'
    );
    $consulta->execute([':email' => $email]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        require_once __DIR__ . '/../../../vendor/autoload.php';
        $correo_config = require __DIR__ . '/../../email_config.php';

        if ($correo_config['username'] === '' || $correo_config['password'] === '') {
            throw new RuntimeException('Faltan las variables SIS_INV_GMAIL y SIS_INV_GMAIL_APP_PASSWORD.');
        }

        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);

        $pdo->beginTransaction();
        $invalidar_tokens = $pdo->prepare(
            'UPDATE tb_password_resets
             SET fecha_uso = NOW()
             WHERE id_usuario = :id_usuario AND fecha_uso IS NULL'
        );
        $invalidar_tokens->execute([':id_usuario' => $usuario['id_usuario']]);

        $guardar_token = $pdo->prepare(
            'INSERT INTO tb_password_resets
                (id_usuario, token_hash, fecha_expiracion)
             VALUES (:id_usuario, :token_hash, DATE_ADD(NOW(), INTERVAL 1 HOUR))'
        );
        $guardar_token->execute([
            ':id_usuario' => $usuario['id_usuario'],
            ':token_hash' => $token_hash,
        ]);
        $id_reset = $pdo->lastInsertId();
        $pdo->commit();

        $correo = new PHPMailer\PHPMailer\PHPMailer(true);
        $correo->isSMTP();
        $correo->Host = 'smtp.gmail.com';
        $correo->SMTPAuth = true;
        $correo->Username = $correo_config['username'];
        $correo->Password = preg_replace('/\s+/', '', $correo_config['password']);
        $correo->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $correo->Port = 587;
        $correo->CharSet = 'UTF-8';
        $correo->setFrom($correo_config['username'], 'Sistema de Inventario');
        $correo->addAddress($usuario['email'], $usuario['nombres']);
        $correo->isHTML(true);
        $correo->Subject = 'Restablecimiento de contraseña';

        $enlace = $URL . '/login/restablecer.php?token=' . urlencode($token);
        $correo->Body = '<p>Hola ' . htmlspecialchars($usuario['nombres'], ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Recibimos una solicitud para restablecer tu contraseña.</p>'
            . '<p><a href="' . htmlspecialchars($enlace, ENT_QUOTES, 'UTF-8') . '">Crear una nueva contraseña</a></p>'
            . '<p>El enlace vence en una hora. Si no solicitaste este cambio, ignora este correo.</p>';
        $correo->AltBody = "Abre este enlace para crear una nueva contraseña (vence en una hora):\n" . $enlace;

        try {
            $correo->send();
        } catch (PHPMailer\PHPMailer\Exception $e) {
            $eliminar_token = $pdo->prepare('DELETE FROM tb_password_resets WHERE id_reset = :id_reset');
            $eliminar_token->execute([':id_reset' => $id_reset]);
            throw $e;
        }
    }
} catch (PDOException $e) {
    error_log('Error al solicitar restablecimiento de contraseña: ' . $e->getMessage());
    $respuesta = 'No se pudo procesar la solicitud. Inténtalo más tarde.';
    $icono = 'error';
} catch (PHPMailer\PHPMailer\Exception $e) {
    error_log('Error enviando correo de restablecimiento: ' . $e->getMessage());
    $respuesta = 'No se pudo enviar el correo. Inténtalo más tarde.';
    $icono = 'error';
} catch (RuntimeException $e) {
    error_log('Configuración de correo incompleta: ' . $e->getMessage());
    $respuesta = 'El servicio de correo no está configurado. Contacta al administrador.';
    $icono = 'error';
}

$_SESSION['mensaje'] = $respuesta;
$_SESSION['icono'] = $icono;
header('Location: ' . $URL . '/login/olvidar.php');
exit;
