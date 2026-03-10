-- Nuevas tablas para gestión de ventas y catálogo
CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_categoria_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_categoria
      FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_productos_categoria (id_categoria),
    INDEX idx_productos_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_compra DATETIME NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    monto_total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) DEFAULT NULL,
    observaciones VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_ventas_cliente
      FOREIGN KEY (id_cliente) REFERENCES clients(id_cliente)
      ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_ventas_producto
      FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_ventas_cliente_fecha (id_cliente, fecha_compra),
    INDEX idx_ventas_producto (id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
