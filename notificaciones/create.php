
<?php

function notificarRoles(
    PDO $pdo,
    array $roles,
    int $id_usuario_origen,
    ?int $id_mantenimiento,
    string $tipo,
    string $titulo,
    string $mensaje
) {
    if (empty($roles)) {
        return 0;
    }

    $roles = array_values(array_unique($roles));

    $marcadores = implode(',', array_fill(0, count($roles), '?'));

    $sql = "SELECT u.id_usuario
            FROM tb_usuarios AS u
            INNER JOIN tb_roles AS r ON u.id_rol = r.id_rol
            WHERE LOWER(TRIM(r.rol)) IN ($marcadores)
            AND u.id_usuario <> ?";

    $consulta = $pdo->prepare($sql);

    $consulta->execute(
        array_merge($roles, [$id_usuario_origen])
    );

    $usuarios = $consulta->fetchAll(PDO::FETCH_COLUMN);

    $insertar = $pdo->prepare(
        "INSERT INTO tb_notificaciones
        (
            id_usuario_destino,
            id_usuario_origen,
            id_mantenimiento,
            tipo,
            titulo,
            mensaje,
            leida,
            fecha_registro
        )
        VALUES
        (
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

    $total = 0;

    foreach ($usuarios as $id_destino) {

        $insertar->execute([
            ':destino' => (int) $id_destino,
            ':origen' => $id_usuario_origen,
            ':mantenimiento' => $id_mantenimiento,
            ':tipo' => $tipo,
            ':titulo' => $titulo,
            ':mensaje' => $mensaje
        ]);

        $total++;
    }

    return $total;
}
