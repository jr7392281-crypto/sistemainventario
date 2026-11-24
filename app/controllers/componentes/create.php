<?php
include('../../config.php');
require_once __DIR__ . '/../../../layout/sesion.php';
require_once __DIR__ . '/../../solo_inventario.php';
require_once __DIR__ . '/../../auditoria.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $URL . '/componentes/');
    exit;
}

$texto = static function ($valor): string {
    return is_string($valor) ? trim($valor) : '';
};
$codigo = $texto($_POST['codigo'] ?? null);
$nombre = $texto($_POST['nombre'] ?? null);
$tipoComponente = $texto($_POST['tipo_componente'] ?? null);
$marca = $texto($_POST['marca'] ?? null);
$especificacion = $texto($_POST['especificacion'] ?? null);
$aplicaA = $texto($_POST['aplica_a'] ?? null);
$stockMinimo = is_scalar($_POST['stock_minimo'] ?? null)
    ? filter_var($_POST['stock_minimo'], FILTER_VALIDATE_INT)
    : false;
$stockActual = is_scalar($_POST['stock_actual'] ?? null)
    ? filter_var($_POST['stock_actual'], FILTER_VALIDATE_INT)
    : false;

if (
    $codigo === '' || $nombre === '' || $tipoComponente === '' ||
    !in_array($aplicaA, ['Equipo', 'Impresora', 'Ambos'], true) ||
    $stockMinimo === false || $stockMinimo === null || $stockMinimo < 0 ||
    $stockActual === false || $stockActual === null || $stockActual < 0
) {
    $_SESSION['mensaje'] = 'Completa los datos y verifica que las cantidades sean números enteros iguales o mayores que cero.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/componentes/create.php');
    exit;
}

try {
    $pdo->beginTransaction();
    $insertar = $pdo->prepare(
        'INSERT INTO tb_componentes
            (codigo, nombre, tipo_componente, aplica_a, marca, especificacion,
             stock_actual, stock_minimo, estado)
         VALUES
            (:codigo, :nombre, :tipo_componente, :aplica_a, :marca, :especificacion,
             :stock_actual, :stock_minimo, 1)'
    );
    $insertar->execute([
        ':codigo' => $codigo,
        ':nombre' => $nombre,
        ':tipo_componente' => $tipoComponente,
        ':aplica_a' => $aplicaA,
        ':marca' => $marca !== '' ? $marca : null,
        ':especificacion' => $especificacion !== '' ? $especificacion : null,
        ':stock_actual' => $stockActual,
        ':stock_minimo' => $stockMinimo,
    ]);

    $id_componente = (int) $pdo->lastInsertId();
    $pdo->commit();
    registrar_auditoria($pdo, 'crear', 'componentes', $id_componente, 'Registró el componente ' . $nombre . ' (' . $codigo . ') con stock inicial de ' . $stockActual . '.');
    $_SESSION['mensaje'] = 'Se registró el componente correctamente.';
    $_SESSION['icono'] = 'success';
    header('Location: ' . $URL . '/componentes/');
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar componente: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo registrar el componente. Revisa los datos e inténtalo otra vez.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/componentes/create.php');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error al registrar componente: ' . $e->getMessage());
    $_SESSION['mensaje'] = 'No se pudo registrar el componente.';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/componentes/create.php');
    exit;
}
