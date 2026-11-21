<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
include('../../solo_administrador.php');
require_once '../../notificaciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

$id_mantenimiento = (int) ($_POST['id_mantenimiento'] ?? 0);
$fecha = $_POST['fecha'] ?? '';
$tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? '';
$observacion = trim($_POST['observacion'] ?? '');
$estado = $_POST['estado'] ?? '';
$estados_permitidos = ['reportado', 'en_proceso', 'resuelto'];
$estado_disponible = (bool) $pdo->query("SHOW COLUMNS FROM tb_mantenimientos LIKE 'estado'")->fetch(PDO::FETCH_ASSOC);

if ($id_mantenimiento < 1 || ($estado_disponible && !in_array($estado, $estados_permitidos, true))) {
    $_SESSION['mensaje'] = 'Revisa los datos del mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
}

try {
    $pdo->beginTransaction();
    $campo_estado = $estado_disponible ? 'estado' : "'reportado' AS estado";
    $consulta_anterior = $pdo->prepare(
        "SELECT id_usuario, $campo_estado FROM tb_mantenimientos
         WHERE id_mantenimiento = :id_mantenimiento FOR UPDATE"
    );
    $consulta_anterior->execute([':id_mantenimiento' => $id_mantenimiento]);
    $mantenimiento_anterior = $consulta_anterior->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento_anterior) {
        $pdo->rollBack();
        $_SESSION['mensaje'] = 'No se encontró el mantenimiento.';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/mantenimiento/');
        exit;
    }

    $sql_actualizar = 'UPDATE tb_mantenimientos SET
            fecha = :fecha,
            tipo_mantenimiento = :tipo_mantenimiento,
            observacion = :observacion';
    if ($estado_disponible) {
        $sql_actualizar .= ', estado = :estado';
    }
    $sql_actualizar .= ' WHERE id_mantenimiento = :id_mantenimiento';
    $actualizar = $pdo->prepare($sql_actualizar);
    $parametros = [
        ':fecha' => $fecha,
        ':tipo_mantenimiento' => $tipo_mantenimiento,
        ':observacion' => $observacion,
        ':id_mantenimiento' => $id_mantenimiento,
    ];
    if ($estado_disponible) {
        $parametros[':estado'] = $estado;
    }
    $actualizar->execute($parametros);

    $notificacion_enviada = true;
    if ($estado_disponible && $mantenimiento_anterior['estado'] !== $estado) {
        $titulo = $estado === 'resuelto'
            ? 'Mantenimiento resuelto'
            : 'Actualización de mantenimiento';
        $mensaje = $estado === 'resuelto'
            ? 'El mantenimiento reportado fue marcado como resuelto.'
            : 'El estado de tu mantenimiento cambió a: ' . estadoMantenimientoEtiqueta($estado) . '.';

        try {
            crearNotificacion(
                $pdo,
                (int) $mantenimiento_anterior['id_usuario'],
                (int) $id_usuario_sesion,
                $id_mantenimiento,
                'estado_mantenimiento',
                $titulo,
                $mensaje
            );
        } catch (PDOException $error_notificaciones) {
            $notificacion_enviada = false;
            error_log('No se pudo enviar el aviso de cambio de estado: ' . $error_notificaciones->getMessage());
        }
    }

    $pdo->commit();
    if (!$estado_disponible) {
        $_SESSION['mensaje'] = 'Se actualizó el mantenimiento. Aplica la migración para activar estados y notificaciones.';
    } elseif (!$notificacion_enviada) {
        $_SESSION['mensaje'] = 'Se actualizó el mantenimiento, pero no se pudo enviar la notificación. Verifica la migración.';
    } else {
        $_SESSION['mensaje'] = 'Se actualizó el mantenimiento correctamente.';
    }
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/mantenimiento/');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al actualizar mantenimiento: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo actualizar el mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/mantenimiento/update.php?id=' . $id_mantenimiento);
    exit;
}
