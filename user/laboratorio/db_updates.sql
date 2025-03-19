-- Tabla de tipos de exámenes
CREATE TABLE IF NOT EXISTS tipos_examenes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    tiempo_estimado INT NOT NULL COMMENT 'Tiempo estimado en minutos',
    requiere_ayuno BOOLEAN DEFAULT FALSE,
    instrucciones_previas TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de exámenes solicitados
CREATE TABLE IF NOT EXISTS examenes_solicitados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_examen_id INT NOT NULL,
    paciente_id INT NOT NULL,
    doctor_id INT NOT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_programada DATETIME,
    estado ENUM('pendiente', 'programado', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
    prioridad ENUM('normal', 'urgente') DEFAULT 'normal',
    notas_medicas TEXT,
    FOREIGN KEY (tipo_examen_id) REFERENCES tipos_examenes(id),
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (doctor_id) REFERENCES usuarios(id)
);

-- Tabla de resultados de exámenes
CREATE TABLE IF NOT EXISTS resultados_examenes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    examen_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    fecha_resultado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resultado_detalle TEXT NOT NULL,
    observaciones TEXT,
    archivo_adjunto VARCHAR(255),
    estado_validacion ENUM('preliminar', 'validado') DEFAULT 'preliminar',
    FOREIGN KEY (examen_id) REFERENCES examenes_solicitados(id),
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id)
);

-- Tabla de valores de referencia
CREATE TABLE IF NOT EXISTS valores_referencia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_examen_id INT NOT NULL,
    parametro VARCHAR(100) NOT NULL,
    unidad VARCHAR(50),
    valor_minimo DECIMAL(10,3),
    valor_maximo DECIMAL(10,3),
    valor_texto TEXT,
    genero ENUM('M', 'F', 'ambos') DEFAULT 'ambos',
    edad_minima INT,
    edad_maxima INT,
    FOREIGN KEY (tipo_examen_id) REFERENCES tipos_examenes(id)
);

-- Tabla de muestras
CREATE TABLE IF NOT EXISTS muestras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    examen_id INT NOT NULL,
    codigo_barras VARCHAR(50) UNIQUE NOT NULL,
    tipo_muestra VARCHAR(50) NOT NULL,
    fecha_recoleccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    recolectado_por INT NOT NULL,
    estado ENUM('pendiente', 'procesada', 'descartada') DEFAULT 'pendiente',
    observaciones TEXT,
    FOREIGN KEY (examen_id) REFERENCES examenes_solicitados(id),
    FOREIGN KEY (recolectado_por) REFERENCES usuarios(id)
);

-- Índices para optimizar búsquedas
CREATE INDEX idx_examenes_fecha ON examenes_solicitados(fecha_solicitud);
CREATE INDEX idx_examenes_estado ON examenes_solicitados(estado);
CREATE INDEX idx_resultados_fecha ON resultados_examenes(fecha_resultado);
CREATE INDEX idx_muestras_codigo ON muestras(codigo_barras);

-- Comentarios de las tablas
ALTER TABLE tipos_examenes COMMENT 'Catálogo de tipos de exámenes disponibles';
ALTER TABLE examenes_solicitados COMMENT 'Registro de solicitudes de exámenes';
ALTER TABLE resultados_examenes COMMENT 'Resultados de los exámenes realizados';
ALTER TABLE valores_referencia COMMENT 'Valores de referencia para cada tipo de examen';
ALTER TABLE muestras COMMENT 'Registro de muestras recolectadas'; 