-- Tabla de medicamentos
CREATE TABLE IF NOT EXISTS medicamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    principio_activo VARCHAR(255) NOT NULL,
    concentracion VARCHAR(100) NOT NULL,
    presentacion VARCHAR(100) NOT NULL,
    laboratorio VARCHAR(255) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    precio DECIMAL(10,2) NOT NULL,
    estado ENUM('disponible', 'agotado', 'descontinuado') NOT NULL DEFAULT 'disponible',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para registrar movimientos de stock
CREATE TABLE IF NOT EXISTS movimientos_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medicamento_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    motivo TEXT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla para recetas médicas
CREATE TABLE IF NOT EXISTS recetas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    consulta_id INT NOT NULL,
    medicamento_id INT NOT NULL,
    dosis VARCHAR(100) NOT NULL,
    frecuencia VARCHAR(100) NOT NULL,
    duracion VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    estado ENUM('pendiente', 'dispensada', 'cancelada') NOT NULL DEFAULT 'pendiente',
    fecha_receta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_dispensacion DATETIME,
    usuario_dispensador_id INT,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id),
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id),
    FOREIGN KEY (usuario_dispensador_id) REFERENCES usuarios(id)
);

-- Índices para mejorar el rendimiento
CREATE INDEX idx_medicamentos_nombre ON medicamentos(nombre);
CREATE INDEX idx_medicamentos_estado ON medicamentos(estado);
CREATE INDEX idx_movimientos_fecha ON movimientos_stock(fecha_movimiento);
CREATE INDEX idx_recetas_estado ON recetas(estado);
CREATE INDEX idx_recetas_fecha ON recetas(fecha_receta); 