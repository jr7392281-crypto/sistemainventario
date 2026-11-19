-- Agrega el rol a una base de datos ya instalada sin duplicarlo.
INSERT INTO tb_roles (rol, fecha_registro)
SELECT 'encargado de inventario', CURRENT_TIMESTAMP()
WHERE NOT EXISTS (
    SELECT 1
    FROM tb_roles
    WHERE rol = 'encargado de inventario'
);
