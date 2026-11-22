<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once '../../componentes_stock.php';

if (strtolower(trim((string) ($rol_sesion ?? ''))) !== 'soporte tecnico') {
    $_SESSION['mensaje'] = 'Solo Soporte técnico puede registrar componentes usados en un mantenimiento.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/componentes/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/componentes/movimientos.php');
    exit;
}

$idComponente = is_scalar($_POST['id_componente'] ?? null)
    ? filter_var($_POST['id_componente'], FILTER_VALIDATE_INT)
    : false;
$cantidad = is_scalar($_POST['cantidad'] ?? null)
    ? filter_var($_POST['cantidad'], FILTER_VALIDATE_INT)
    : false;
$valorMantenimiento = $_POST['id_mantenimiento'] ?? '';
$idMantenimiento = $valorMantenimiento === ''
    ? null
    : (is_scalar($valorMantenimiento) ? filter_var($valorMantenimiento, FILTER_VALIDATE_INT) : false);
$url_retorno = is_int($idMantenimiento) && $idMantenimiento > 0
    ? $URL . '/mantenimiento/show.php?id=' . $idMantenimiento
    : $URL . '/componentes/movimientos.php';
$observacion = is_string($_POST['observacion'] ?? null) ? trim($_POST['observacion']) : '';

if (
    $idComponente === false || $idComponente === null || $idComponente < 1 ||
    $cantidad === false || $cantidad === null || $cantidad < 1 ||
    ($idMantenimiento !== null && ($idMantenimiento === false || $idMantenimiento < 1))
) {
    $_SESSION['mensaje'] = 'Revisa el componente, el mantenimiento y la cantidad.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $url_retorno);
    exit;
}

if ($idMantenimiento === null) {
    $_SESSION['mensaje'] = 'Selecciona el mantenimiento donde se usó el componente.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $url_retorno);
    exit;
}

try {
    $pdo->beginTransaction();

    $consultaMantenimiento = $pdo->prepare(
        "SELECT estado, id_tecnico_asignado
         FROM tb_mantenimientos
         WHERE id_mantenimiento = :id_mantenimiento
         FOR UPDATE"
    );
    $consultaMantenimiento->execute([':id_mantenimiento' => (int) $idMantenimiento]);
    $mantenimiento = $consultaMantenimiento->fetch(PDO::FETCH_ASSOC);

    if (
        !$mantenimiento ||
        $mantenimiento['estado'] !== 'aceptado' ||
        (int) $mantenimiento['id_tecnico_asignado'] !== (int) $id_usuario_sesion
    ) {
        throw new DomainException('Solo puedes registrar componentes en un mantenimiento aceptado por ti.');
    }

    registrarUsoComponente(
        $pdo,
        (int) $idComponente,
        (int) $id_usuario_sesion,
        (int) $idMantenimiento,
        (int) $cantidad,
        $observacion
    );
    $pdo->commit();

    $_SESSION['mensaje'] = 'Se registró el componente usado y se actualizó el stock.';
    $_SESSION['icono'] = 'success';
} catch (DomainException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['icono'] = 'error';
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar movimiento de stock: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo registrar el movimiento.';
    $_SESSION['icono'] = 'error';
}

header('Location: ' . $url_retorno);
exit;
