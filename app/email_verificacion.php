<?php

function enviar_enlace_verificacion(PDO $pdo, array $usuario, string $URL): void
{
    require_once __DIR__ . '/../vendor/autoload.php';
    $correo_config = require __DIR__ . '/email_config.php';

    if (($correo_config['username'] ?? '') === '' || ($correo_config['password'] ?? '') === '') {
        throw new RuntimeException('Falta configurar el correo del sistema.');
    }

    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);

    $pdo->beginTransaction();
    try {
        $eliminar_anterior = $pdo->prepare(
            'DELETE FROM tb_email_verification WHERE id_usuario = :id_usuario'
        );
        $eliminar_anterior->execute([':id_usuario' => (int) $usuario['id_usuario']]);

        $guardar_token = $pdo->prepare(
            'INSERT INTO tb_email_verification (id_usuario, token_hash, fecha_expiracion)
             VALUES (:id_usuario, :token_hash, DATE_ADD(NOW(), INTERVAL 24 HOUR))'
        );
        $guardar_token->execute([
            ':id_usuario' => (int) $usuario['id_usuario'],
            ':token_hash' => $token_hash,
        ]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

    try {
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
        $correo->Subject = 'Verifica tu correo - Sistema de Inventario';

        $enlace = $URL . '/app/controllers/login/verificar_correo.php?token=' . urlencode($token);
        $nombre = htmlspecialchars((string) $usuario['nombres'], ENT_QUOTES, 'UTF-8');
        $enlace_html = htmlspecialchars($enlace, ENT_QUOTES, 'UTF-8');

        $correo->Body = '<p>Hola ' . $nombre . ',</p>'
            . '<p>Para confirmar tu correo, abre este enlace:</p>'
            . '<p><a href="' . $enlace_html . '">Verificar mi correo</a></p>'
            . '<p>El enlace vence en 24 horas y solo puede utilizarse una vez.</p>';
        $correo->AltBody = "Abre este enlace para verificar tu correo. Vence en 24 horas:\n" . $enlace;
        $correo->send();
    } catch (Throwable $e) {
        try {
            $eliminar_token = $pdo->prepare(
                'DELETE FROM tb_email_verification
                 WHERE id_usuario = :id_usuario AND token_hash = :token_hash'
            );
            $eliminar_token->execute([
                ':id_usuario' => (int) $usuario['id_usuario'],
                ':token_hash' => $token_hash,
            ]);
        } catch (Throwable $error_limpieza) {
            error_log('No se pudo limpiar el token de verificación: ' . $error_limpieza->getMessage());
        }
        throw $e;
    }
}
