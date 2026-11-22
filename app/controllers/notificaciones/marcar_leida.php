<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/index.php');
    exit;
}

$es_ajax = ($_POST['ajax'] ?? '') === '1';
$token = $_POST['csrf_token'] ?? '';
$token_valido = is_string($token)
    && !empty($_SESSION['csrf_notificaciones'])
    && hash_equals($_SESSION['csrf_notificaciones'], $token);

if (!$token_valido) {
    if ($es_ajax) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'mensaje' => 'La solicitud venció. Inténtalo nuevamente.']);
        exit;
    }

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

    if ($es_ajax) {
        $consulta_pendientes = $pdo->prepare(
            'SELECT COUNT(*) FROM tb_notificaciones
             WHERE id_usuario_destino = :id_usuario AND leida = 0'
        );
        $consulta_pendientes->execute([':id_usuario' => $id_usuario_sesion]);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok' => true,
            'no_leidas' => (int) $consulta_pendientes->fetchColumn()
        ]);
        exit;
    }
} catch (Throwable $e) {
    error_log('Error al marcar notificación: ' . $e->getMessage());

    if ($es_ajax) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'mensaje' => 'No se pudo actualizar la notificación.']);
        exit;
    }

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
