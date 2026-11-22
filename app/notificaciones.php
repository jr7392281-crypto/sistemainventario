
<?php

/**
 * Crear una notificación para un usuario específico.
 */
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
        "INSERT INTO tb_notificaciones (
            id_usuario_destino,
            id_usuario_origen,
            id_mantenimiento,
            tipo,
            titulo,
            mensaje,
            leida,
            fecha_registro
        ) VALUES (
            :destino,
            :origen,
            :mantenimiento,
            :tipo,
            :titulo,
            :mensaje,
            0,
            NOW()
        )"
    );

    $consulta->execute([
        ':destino' => $idUsuarioDestino,
        ':origen' => $idUsuarioOrigen,
        ':mantenimiento' => $idMantenimiento,
        ':tipo' => $tipo,
        ':titulo' => $titulo,
        ':mensaje' => $mensaje
    ]);
}


/**
 * Notificar a los usuarios que tengan determinados roles.
 * Devuelve la cantidad de notificaciones creadas.
 */
function notificarRoles(
    PDO $pdo,
    array $roles,
    int $idUsuarioOrigen,
    ?int $idMantenimiento,
    string $tipo,
    string $titulo,
    string $mensaje
): int {

    if (empty($roles)) {
        return 0;
    }

    // Normalizar los nombres de los roles.
    $roles = array_values(array_unique(array_filter(
        array_map(
            fn($rol) => strtolower(trim((string) $rol)),
            $roles
        ),
        fn($rol) => $rol !== ''
    )));

    if (empty($roles)) {
        return 0;
    }

    $marcadores = [];
    $parametros = [
        ':id_usuario_origen' => $idUsuarioOrigen
    ];

    foreach ($roles as $indice => $rol) {
        $marcador = ':rol_' . $indice;
        $marcadores[] = $marcador;
        $parametros[$marcador] = $rol;
    }

    $sql = "SELECT u.id_usuario
            FROM tb_usuarios AS u
            INNER JOIN tb_roles AS r
                ON r.id_rol = u.id_rol
            WHERE LOWER(TRIM(r.rol)) IN (
                " . implode(', ', $marcadores) . "
            )
            AND u.id_usuario <> :id_usuario_origen";

    $consulta = $pdo->prepare($sql);
    $consulta->execute($parametros);

    $usuarios = $consulta->fetchAll(PDO::FETCH_COLUMN);

    $total = 0;

    foreach ($usuarios as $idUsuarioDestino) {

        crearNotificacion(
            $pdo,
            (int) $idUsuarioDestino,
            $idUsuarioOrigen,
            $idMantenimiento,
            $tipo,
            $titulo,
            $mensaje
        );

        $total++;
    }

    return $total;
}


/**
 * Obtener la etiqueta correspondiente al estado.
 */
function estadoMantenimientoEtiqueta(string $estado): string
{
    $etiquetas = [
        'reportado' => 'Pendiente de revisión',
        'enviado' => 'Enviado a Soporte técnico',
        'aceptado' => 'Mantenimiento aceptado',
        'rechazado' => 'Mantenimiento rechazado',
        'resuelto' => 'Mantenimiento resuelto'
    ];

    return $etiquetas[strtolower(trim($estado))] ?? 'Desconocido';
}

?>
