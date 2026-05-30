CREATE TABLE IF NOT EXISTS productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(60) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    imagen_url VARCHAR(500) NOT NULL,
    destacado TINYINT(1) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contactos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL,
    mensaje TEXT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO productos
    (id, nombre, categoria, descripcion, precio, stock, imagen_url, destacado)
VALUES
    (
        1,
        'Laptop Ultraliviana Nova 14',
        'Computadoras',
        'Portátil de 14 pulgadas con disco SSD, 16 GB de RAM y autonomía para clases, trabajo remoto y edición ligera.',
        899.00,
        8,
        'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80',
        1
    ),
    (
        2,
        'Audífonos Studio Beat',
        'Audio',
        'Audífonos inalámbricos con cancelación de ruido, micrófono integrado y estuche rígido para transporte.',
        129.99,
        18,
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80',
        1
    ),
    (
        3,
        'Smartwatch Active Pro',
        'Wearables',
        'Reloj inteligente con medición de ritmo cardiaco, notificaciones, seguimiento deportivo y resistencia al agua.',
        179.50,
        12,
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80',
        0
    ),
    (
        4,
        'Cámara Mirrorless X200',
        'Fotografía',
        'Cámara compacta con sensor de alta resolución, grabación 4K y lente intercambiable para creadores de contenido.',
        640.00,
        5,
        'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80',
        0
    ),
    (
        5,
        'Teclado Mecánico RGB',
        'Accesorios',
        'Teclado mecánico con retroiluminación configurable, switches táctiles y estructura compacta para escritorio.',
        74.90,
        24,
        'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80',
        0
    ),
    (
        6,
        'Monitor Vision 27',
        'Monitores',
        'Monitor de 27 pulgadas con panel IPS, resolución QHD y bordes delgados para productividad y entretenimiento.',
        299.00,
        10,
        'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80',
        1
    )
ON DUPLICATE KEY UPDATE
    nombre = VALUES(nombre),
    categoria = VALUES(categoria),
    descripcion = VALUES(descripcion),
    precio = VALUES(precio),
    stock = VALUES(stock),
    imagen_url = VALUES(imagen_url),
    destacado = VALUES(destacado);
