-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS clinica;
USE clinica;

-- Tabla de Especialidades
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Personal (Médicos y otros empleados)
CREATE TABLE personal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo ENUM('Doctor', 'Enfermero', 'Farmaceutico', 'Otro') NOT NULL,
    especialidad_id INT,
    dni VARCHAR(20) UNIQUE,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Servicios
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion INT COMMENT 'Duración en minutos',
    precio DECIMAL(10,2),
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Pacientes
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(20) UNIQUE,
    fecha_nacimiento DATE,
    genero ENUM('M', 'F', 'Otro'),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Historiales Clínicos
CREATE TABLE historiales_clinicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    fecha_consulta DATETIME NOT NULL,
    medico_id INT NOT NULL,
    diagnostico TEXT,
    tratamiento TEXT,
    observaciones TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES personal(id)
);

-- Tabla de Citas
CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    motivo TEXT,
    estado ENUM('Pendiente', 'Confirmada', 'Completada', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (medico_id) REFERENCES personal(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Horarios
CREATE TABLE horarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medico_id INT NOT NULL,
    dia_semana INT NOT NULL COMMENT '1=Domingo, 2=Lunes, ..., 7=Sábado',
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    estado TINYINT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (medico_id) REFERENCES personal(id) ON DELETE CASCADE,
    CONSTRAINT chk_dia_semana CHECK (dia_semana BETWEEN 1 AND 7),
    CONSTRAINT chk_horas CHECK (hora_inicio < hora_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Horarios del Personal
CREATE TABLE horarios_personal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medico_id INT,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
    hora_inicio TIME,
    hora_fin TIME,
    FOREIGN KEY (medico_id) REFERENCES personal(id)
);

-- Tabla de Horarios Bloqueados
CREATE TABLE horarios_bloqueados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medico_id INT,
    fecha_inicio DATETIME,
    fecha_fin DATETIME,
    motivo TEXT,
    FOREIGN KEY (medico_id) REFERENCES personal(id)
);

-- Tabla de Stock de Medicamentos
CREATE TABLE medicamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    stock INT DEFAULT 0,
    precio DECIMAL(10,2),
    estado ENUM('Disponible', 'Agotado', 'Descontinuado') DEFAULT 'Disponible'
);

-- Tabla de Recetas
CREATE TABLE recetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    historial_id INT NOT NULL,
    medicamento_id INT NOT NULL,
    dosis VARCHAR(100),
    frecuencia VARCHAR(100),
    duracion VARCHAR(100),
    fecha_emision DATE,
    estado ENUM('Activa', 'Completada', 'Cancelada') DEFAULT 'Activa',
    FOREIGN KEY (historial_id) REFERENCES historiales_clinicos(id),
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id)
);

-- Agregar claves foráneas a la tabla `recetas`
ALTER TABLE recetas
ADD CONSTRAINT fk_paciente
FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
ON DELETE CASCADE,
ADD CONSTRAINT fk_medico
FOREIGN KEY (medico_id) REFERENCES personal(id)
ON DELETE CASCADE; 
-- Tabla de Detalles de Receta
CREATE TABLE detalles_receta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receta_id INT,
    medicamento_id INT,
    dosis VARCHAR(100),
    frecuencia VARCHAR(100),
    duracion VARCHAR(100),
    instrucciones TEXT,
    FOREIGN KEY (receta_id) REFERENCES recetas(id),
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id)
);

-- Tabla de Pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cita_id INT,
    monto DECIMAL(10,2),
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    metodo_pago ENUM('Efectivo', 'Tarjeta', 'Transferencia'),
    estado ENUM('Pendiente', 'Completado', 'Cancelado') DEFAULT 'Pendiente',
    FOREIGN KEY (cita_id) REFERENCES citas(id)
);

-- Eliminar la tabla usuarios si existe
DROP TABLE IF EXISTS usuarios;

-- Crear la tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'doctor', 'recepcionista', 'enfermera', 'farmaceutico') NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de prueba
INSERT INTO usuarios (username, password, nombre, rol, estado) VALUES 
('admin', '123456', 'Administrador', 'admin', 'Activo'),
('doctor1', '123456', 'Doctor Ejemplo', 'doctor', 'Activo'),
('recep1', '123456', 'Recepcionista Ejemplo', 'recepcionista', 'Activo');

-- Verificar los usuarios insertados
SELECT * FROM usuarios;

-- Datos de ejemplo para las tablas
INSERT INTO especialidades (nombre, descripcion) VALUES
('Medicina General', 'Atención médica general y preventiva'),
('Pediatría', 'Especialidad médica para niños'),
('Cardiología', 'Especialidad en enfermedades del corazón');

INSERT INTO personal (nombre, apellidos, dni, especialidad_id) VALUES
('Juan', 'Pérez', '12345678', 1),
('María', 'García', '87654321', 2),
('Carlos', 'López', '11223344', 3);

INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES
('Consulta General', 'Consulta médica general', 30, 50.00),
('Control Pediátrico', 'Control de rutina pediátrico', 45, 70.00),
('Electrocardiograma', 'Estudio del corazón', 60, 120.00);

INSERT INTO horarios (medico_id, dia_semana, hora_inicio, hora_fin) VALUES
(1, 2, '08:00:00', '13:00:00'), -- Lunes mañana
(1, 2, '15:00:00', '18:00:00'), -- Lunes tarde
(1, 3, '08:00:00', '13:00:00'), -- Martes mañana
(1, 3, '15:00:00', '18:00:00'), -- Martes tarde
(2, 2, '09:00:00', '14:00:00'), -- Lunes
(2, 4, '09:00:00', '14:00:00'), -- Miércoles
(3, 3, '14:00:00', '20:00:00'), -- Martes
(3, 5, '14:00:00', '20:00:00'); -- Jueves

-- Índices para mejorar el rendimiento
CREATE INDEX idx_citas_fecha ON citas(fecha_hora);
CREATE INDEX idx_personal_especialidad ON personal(especialidad_id);
CREATE INDEX idx_pacientes_dni ON pacientes(dni);

-- Módulos del sistema
-- admin/
-- ├── modules/
-- │   ├── personal/         # Gestión de médicos y personal
-- │   ├── pacientes/        # Gestión de pacientes
-- │   ├── citas/           # Gestión de citas
-- │   ├── historiales/     # Historiales clínicos
-- │   ├── recetas/         # Gestión de recetas
-- │   ├── medicamentos/    # Control de stock
-- │   └── reportes/        # Estadísticas y reportes 

-- Modificar la tabla usuarios para asegurar que password no sea nulo
ALTER TABLE usuarios MODIFY COLUMN password VARCHAR(255) NOT NULL; 