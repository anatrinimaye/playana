-- Tabla de consultas
CREATE TABLE IF NOT EXISTS consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    doctor_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    presion_arterial VARCHAR(20),
    temperatura DECIMAL(4,1),
    frecuencia_cardiaca INT,
    peso DECIMAL(5,2),
    motivo TEXT NOT NULL,
    sintomas TEXT NOT NULL,
    diagnostico TEXT NOT NULL,
    tratamiento TEXT NOT NULL,
    estado ENUM('Activa', 'Completada', 'Cancelada') DEFAULT 'Activa',
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (doctor_id) REFERENCES personal(id)
);

-- Tabla de historial médico
CREATE TABLE IF NOT EXISTS historial_medico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    consulta_id INT NOT NULL,
    fecha DATETIME NOT NULL,
    diagnostico TEXT NOT NULL,
    tratamiento TEXT NOT NULL,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (consulta_id) REFERENCES consultas(id)
);

-- Tabla de recetas
CREATE TABLE IF NOT EXISTS recetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL,
    medicamento_id INT NOT NULL,
    dosis VARCHAR(100) NOT NULL,
    frecuencia VARCHAR(100) NOT NULL,
    duracion VARCHAR(100) NOT NULL,
    fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Activa', 'Dispensada', 'Cancelada') DEFAULT 'Activa',
    FOREIGN KEY (consulta_id) REFERENCES consultas(id),
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id)
);

-- Tabla de exámenes solicitados
CREATE TABLE IF NOT EXISTS examenes_solicitados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL,
    descripcion TEXT NOT NULL,
    notas TEXT,
    fecha_solicitud DATETIME NOT NULL,
    estado ENUM('Pendiente', 'Realizado', 'Cancelado') DEFAULT 'Pendiente',
    resultado TEXT,
    fecha_resultado DATETIME,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id)
);

-- Tabla de resultados de laboratorio
CREATE TABLE IF NOT EXISTS resultados_laboratorio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    examen_id INT NOT NULL,
    archivo_adjunto VARCHAR(255),
    observaciones TEXT,
    fecha_registro DATETIME NOT NULL,
    registrado_por INT NOT NULL,
    FOREIGN KEY (examen_id) REFERENCES examenes_solicitados(id),
    FOREIGN KEY (registrado_por) REFERENCES personal(id)
); 