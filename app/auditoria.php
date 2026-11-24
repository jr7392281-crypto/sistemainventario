<?php

/**
 * Guarda una acción del sistema. Esta función no interrumpe la operación
 * principal si el registro de auditoría falla; deja el detalle en el log PHP.
 */
function registrar_auditoria(
    PDO $pdo,
    string $accion,
    string $modulo,
    ?int $id_registro,
    string $descripcion,
    ?int $id_usuario_actor = null
): void {
    try {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $id_usuario = null;
        $usuario_nombre = null;
        $usuario_rol = null;
        $email_sesion = trim((string) ($_SESSION['sesion_email'] ?? ''));

        if ($id_usuario_actor !== null) {
            $consulta_usuario = $pdo->prepare(
                'SELECT u.id_usuario, u.nombres, r.rol
                 FROM tb_usuarios u
                 INNER JOIN tb_roles r ON r.id_rol = u.id_rol
                 WHERE u.id_usuario = :id_usuario
                 LIMIT 1'
            );
            $consulta_usuario->execute([':id_usuario' => $id_usuario_actor]);
            $usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);
        } elseif ($email_sesion !== '') {
            $consulta_usuario = $pdo->prepare(
                'SELECT u.id_usuario, u.nombres, r.rol
                 FROM tb_usuarios u
                 INNER JOIN tb_roles r ON r.id_rol = u.id_rol
                 WHERE u.email = :email
                 LIMIT 1'
            );
            $consulta_usuario->execute([':email' => $email_sesion]);
            $usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);
        } else {
            $usuario = false;
        }

        if ($usuario) {
            $id_usuario = (int) $usuario['id_usuario'];
            $usuario_nombre = (string) $usuario['nombres'];
            $usuario_rol = (string) $usuario['rol'];
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        if (!is_string($ip) || strlen($ip) > 45) {
            $ip = null;
        }

        $insertar = $pdo->prepare(
            'INSERT INTO tb_auditoria
                (id_usuario, usuario_nombre, usuario_rol, accion, modulo,
                 id_registro, descripcion, ip)
             VALUES
                (:id_usuario, :usuario_nombre, :usuario_rol, :accion, :modulo,
                 :id_registro, :descripcion, :ip)'
        );
        $insertar->bindValue(
            ':id_usuario',
            $id_usuario,
            $id_usuario === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $insertar->bindValue(':usuario_nombre', $usuario_nombre, $usuario_nombre === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $insertar->bindValue(':usuario_rol', $usuario_rol, $usuario_rol === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $insertar->bindValue(':accion', $accion, PDO::PARAM_STR);
        $insertar->bindValue(':modulo', $modulo, PDO::PARAM_STR);
        $insertar->bindValue(':id_registro', $id_registro, $id_registro === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $insertar->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $insertar->bindValue(':ip', $ip, $ip === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $insertar->execute();
    } catch (Throwable $e) {
        error_log('No se pudo guardar la auditoría: ' . $e->getMessage());
    }
}
