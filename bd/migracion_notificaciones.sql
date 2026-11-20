-- Ejecutar una sola vez sobre una base inventario que ya está creada.
ALTER TABLE tb_mantenimientos
    ADD COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'reportado' AFTER observacion;

CREATE TABLE tb_notificaciones (
    id_notificacion INT NOT NULL AUTO_INCREMENT,
    id_usuario_destino INT NOT NULL,
    id_usuario_origen INT DEFAULT NULL,
    id_mantenimiento INT DEFAULT NULL,
    tipo VARCHAR(40) NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    mensaje VARCHAR(255) NOT NULL,
    leida TINYINT(1) NOT NULL DEFAULT 0,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura DATETIME DEFAULT NULL,
    PRIMARY KEY (id_notificacion),
    KEY idx_notificaciones_destino_leida_fecha
        (id_usuario_destino, leida, fecha_registro),
    KEY idx_notificaciones_mantenimiento (id_mantenimiento),
    CONSTRAINT fk_notificaciones_destino
        FOREIGN KEY (id_usuario_destino) REFERENCES tb_usuarios(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notificaciones_origen
        FOREIGN KEY (id_usuario_origen) REFERENCES tb_usuarios(id_usuario)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_notificaciones_mantenimiento
        FOREIGN KEY (id_mantenimiento) REFERENCES tb_mantenimientos(id_mantenimiento)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
