<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/index.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (
    !is_string($token) ||
    empty($_SESSION['csrf_notificaciones']) ||
    !hash_equals($_SESSION['csrf_notificaciones'], $token)
) {
    $_SESSION['mensaje'] = 'La solicitud venció. Inténtalo nuevamente.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
    exit;
}

try {
    if (isset($_POST['marcar_todas'])) {
        $consulta = $pdo->prepare(
            'UPDATE tb_notificaciones
             SET leida = 1, fecha_lectura = NOW()
             WHERE id_usuario_destino = :id_usuario AND leida = 0'
        );
        $consulta->execute([':id_usuario' => $id_usuario_sesion]);
    } else {
        $id_notificacion = filter_input(INPUT_POST, 'id_notificacion', FILTER_VALIDATE_INT);
        if (!$id_notificacion || $id_notificacion < 1) {
            throw new InvalidArgumentException('ID de notificación no válido.');
        }

        $consulta = $pdo->prepare(
            'UPDATE tb_notificaciones
             SET leida = 1, fecha_lectura = NOW()
             WHERE id_notificacion = :id_notificacion
               AND id_usuario_destino = :id_usuario'
        );
        $consulta->execute([
            ':id_notificacion' => $id_notificacion,
            ':id_usuario' => $id_usuario_sesion,
        ]);
    }

    $_SESSION['mensaje'] = 'Notificaciones actualizadas.';
    $_SESSION['icono'] = 'success';
} catch (Throwable $e) {
    error_log('Error al marcar notificación: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo actualizar la notificación.';
    $_SESSION['icono'] = 'error';
}

$return_to = $_POST['return_to'] ?? '/index.php';
$partes_retorno = is_string($return_to) ? parse_url($return_to) : false;
$ruta_retorno = is_array($partes_retorno) ? ($partes_retorno['path'] ?? '') : '';
$consulta_retorno = is_array($partes_retorno) ? ($partes_retorno['query'] ?? '') : '';
if (
    !is_array($partes_retorno) ||
    isset($partes_retorno['scheme']) ||
    isset($partes_retorno['host']) ||
    $ruta_retorno === '' ||
    $ruta_retorno[0] !== '/' ||
    strpos($ruta_retorno, '//') === 0 ||
    strpos($ruta_retorno, "\\") !== false
) {
    $ruta_retorno = '/index.php';
    $consulta_retorno = '';
}
$destino = rtrim($URL, '/') . $ruta_retorno;
if ($consulta_retorno !== '') {
    $destino .= '?' . str_replace(["\r", "\n"], '', $consulta_retorno);
}
header('Location: ' . $destino);
exit;
