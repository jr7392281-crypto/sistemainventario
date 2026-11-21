<?php

function crearNotificacion(
    PDO $pdo,
    int $idUsuarioDestino,
    ?int $idUsuarioOrigen,
    ?int $idMantenimiento,
    string $tipo,
    string $titulo,
    string $mensaje
): void {
    $consulta = $pdo->prepare(
        'INSERT INTO tb_notificaciones
            (id_usuario_destino, id_usuario_origen, id_mantenimiento,
             tipo, titulo, mensaje)
         VALUES
            (:id_usuario_destino, :id_usuario_origen, :id_mantenimiento,
             :tipo, :titulo, :mensaje)'
    );
    $consulta->execute([
        ':id_usuario_destino' => $idUsuarioDestino,
        ':id_usuario_origen' => $idUsuarioOrigen,
        ':id_mantenimiento' => $idMantenimiento,
        ':tipo' => $tipo,
        ':titulo' => $titulo,
        ':mensaje' => $mensaje,
    ]);
}

function notificarRoles(
    PDO $pdo,
    array $roles,
    int $idUsuarioOrigen,
    int $idMantenimiento,
    string $tipo,
    string $titulo,
    string $mensaje
): void {
    if (empty($roles)) {
        return;
    }

    $marcadores = [];
    $parametros = [':id_usuario_origen' => $idUsuarioOrigen];
    foreach (array_values($roles) as $indice => $rol) {
        $marcador = ':rol_' . $indice;
        $marcadores[] = $marcador;
        $parametros[$marcador] = $rol;
    }

    $sql = 'SELECT u.id_usuario
            FROM tb_usuarios AS u
            INNER JOIN tb_roles AS r ON r.id_rol = u.id_rol
            WHERE r.rol IN (' . implode(', ', $marcadores) . ')
              AND u.id_usuario <> :id_usuario_origen';
    $consulta = $pdo->prepare($sql);
    $consulta->execute($parametros);

    foreach ($consulta->fetchAll(PDO::FETCH_COLUMN) as $idUsuarioDestino) {
        crearNotificacion(
            $pdo,
            (int) $idUsuarioDestino,
            $idUsuarioOrigen,
            $idMantenimiento,
            $tipo,
            $titulo,
            $mensaje
        );
    }
}

function estadoMantenimientoEtiqueta(string $estado): string
{
    $etiquetas = [
        'reportado' => 'Reportado',
        'en_proceso' => 'En proceso',
        'resuelto' => 'Resuelto',
    ];

    return $etiquetas[$estado] ?? 'Desconocido';
}
