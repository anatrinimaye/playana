-- Tabla de especialidades
show databases;
use clinica;



CREATE TABLE IF NOT EXISTS especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de relación entre personal y especialidades
CREATE TABLE IF NOT EXISTS personal_especialidades (
    personal_id INT,
    especialidad_id INT,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (personal_id, especialidad_id),
    FOREIGN KEY (personal_id) REFERENCES personal(id) ON DELETE CASCADE,
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id) ON DELETE CASCADE
);

-- Modificaciones a la tabla de personal
ALTER TABLE personal
ADD COLUMN fecha_nacimiento DATE,
ADD COLUMN genero ENUM('M', 'F', 'O'),
ADD COLUMN num_colegiado VARCHAR(50),
ADD COLUMN cv TEXT;
 