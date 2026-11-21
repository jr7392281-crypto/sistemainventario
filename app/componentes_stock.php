<?php

function registrarUsoComponente(
    PDO $pdo,
    int $idComponente,
    int $idUsuario,
    int $idMantenimiento,
    int $cantidad,
    string $observacion
): void {
    if ($cantidad < 1) {
        throw new DomainException('La cantidad debe ser mayor que cero.');
    }

    $consultaComponente = $pdo->prepare(
        'SELECT stock_actual, estado, aplica_a
         FROM tb_componentes
         WHERE id_componente = :id_componente
         FOR UPDATE'
    );
    $consultaComponente->execute([':id_componente' => $idComponente]);
    $componente = $consultaComponente->fetch(PDO::FETCH_ASSOC);

    if (!$componente || (int) $componente['estado'] !== 1) {
        throw new DomainException('El componente no existe o no está activo.');
    }

    $consultaMantenimiento = $pdo->prepare(
        'SELECT id_equipo, id_impresora
         FROM tb_mantenimientos
         WHERE id_mantenimiento = :id_mantenimiento
         FOR UPDATE'
    );
    $consultaMantenimiento->execute([':id_mantenimiento' => $idMantenimiento]);
    $mantenimiento = $consultaMantenimiento->fetch(PDO::FETCH_ASSOC);

    if (!$mantenimiento) {
        throw new DomainException('El mantenimiento seleccionado no existe.');
    }

    $esEquipo = $mantenimiento['id_equipo'] !== null;
    $esImpresora = $mantenimiento['id_impresora'] !== null;

    if ($esEquipo === $esImpresora) {
        throw new DomainException('El mantenimiento debe estar asociado a un equipo o una impresora.');
    }

    $aplicaA = $componente['aplica_a'];
    $compatible = $aplicaA === 'Ambos'
        || ($aplicaA === 'Equipo' && $esEquipo)
        || ($aplicaA === 'Impresora' && $esImpresora);

    if (!$compatible) {
        throw new DomainException('Este componente no corresponde al activo del mantenimiento.');
    }

    $stockActual = (int) $componente['stock_actual'];
    if ($stockActual < $cantidad) {
        throw new DomainException('Stock insuficiente. Disponible: ' . $stockActual . '.');
    }

    $descontarStock = $pdo->prepare(
        'UPDATE tb_componentes
         SET stock_actual = stock_actual - :cantidad_resta
         WHERE id_componente = :id_componente
           AND stock_actual >= :cantidad_minima'
    );
    $descontarStock->execute([
        ':cantidad_resta' => $cantidad,
        ':id_componente' => $idComponente,
        ':cantidad_minima' => $cantidad,
    ]);

    if ($descontarStock->rowCount() !== 1) {
        throw new DomainException('No se pudo descontar el stock disponible.');
    }

    $registrarMovimiento = $pdo->prepare(
        "INSERT INTO tb_movimientos_stock
            (id_componente, id_usuario, id_mantenimiento, tipo, cantidad, observacion)
         VALUES
            (:id_componente, :id_usuario, :id_mantenimiento, 'salida', :cantidad, :observacion)"
    );
    $registrarMovimiento->execute([
        ':id_componente' => $idComponente,
        ':id_usuario' => $idUsuario,
        ':id_mantenimiento' => $idMantenimiento,
        ':cantidad' => $cantidad,
        ':observacion' => $observacion !== '' ? $observacion : null,
    ]);
}
