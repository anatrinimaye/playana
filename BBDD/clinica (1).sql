-- Active: 1728286642113@@127.0.0.1@3306@clinica



-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS clinica;
USE clinica;

-- Tabla: pacientes
CREATE TABLE pacientes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  dni VARCHAR(20) UNIQUE DEFAULT NULL,
  fecha_nacimiento DATE DEFAULT NULL,
  genero ENUM('M', 'F', 'Otro') DEFAULT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  direccion TEXT DEFAULT NULL,
  foto VARCHAR(255) DEFAULT NULL,
  estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: personal
CREATE TABLE personal (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  tipo ENUM('Doctor', 'Enfermero', 'Farmaceutico', 'Otro') NOT NULL,
  dni VARCHAR(20) UNIQUE DEFAULT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  direccion TEXT DEFAULT NULL,
  estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  foto VARCHAR(255) DEFAULT NULL,
  fecha_nacimiento DATE DEFAULT NULL,
  genero ENUM('M', 'F', 'O') DEFAULT NULL,
  num_colegiado VARCHAR(50) DEFAULT NULL,
  cv TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Tabla: citas
CREATE TABLE citas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  servicio INT(11) NOT NULL,
  paciente_id INT(11) NOT NULL,
  medico_id INT(11) NOT NULL,
  fecha_hora DATETIME NOT NULL,
  motivo TEXT DEFAULT NULL,
  estado ENUM('Pendiente', 'Confirmada', 'Completada', 'Cancelada') DEFAULT 'Pendiente',
  correo VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY (medico_id) REFERENCES personal(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: consultas
CREATE TABLE consultas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  paciente_id INT(11) NOT NULL,
  doctor_id INT(11) NOT NULL,
  fecha_hora DATETIME NOT NULL,
  presion_arterial VARCHAR(20) DEFAULT NULL,
  temperatura DECIMAL(4,1) DEFAULT NULL,
  frecuencia_cardiaca INT(11) DEFAULT NULL,
  peso DECIMAL(5,2) DEFAULT NULL,
  motivo TEXT NOT NULL,
  sintomas TEXT NOT NULL,
  diagnostico TEXT NOT NULL,
  tratamiento TEXT NOT NULL,
  estado ENUM('Activa', 'Completada', 'Cancelada') DEFAULT 'Activa',
  PRIMARY KEY (id),
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES personal(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `detalles_receta`
CREATE TABLE detalles_receta (
  id INT(11) NOT NULL AUTO_INCREMENT,
  receta_id INT(11) DEFAULT NULL,
  medicamento_id INT(11) DEFAULT NULL,
  dosis VARCHAR(100) DEFAULT NULL,
  frecuencia VARCHAR(100) DEFAULT NULL,
  duracion VARCHAR(100) DEFAULT NULL,
  instrucciones TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `especialidades`
CREATE TABLE especialidades (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `historiales_clinicos`
CREATE TABLE historiales_clinicos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  paciente_id INT(11) NOT NULL,
  fecha_consulta DATETIME NOT NULL,
  medico_id INT(11) NOT NULL,
  diagnostico TEXT DEFAULT NULL,
  tratamiento TEXT DEFAULT NULL,
  observaciones TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `historial_medico`
CREATE TABLE historial_medico (
  id INT(11) NOT NULL AUTO_INCREMENT,
  paciente_id INT(11) NOT NULL,
  consulta_id INT(11) NOT NULL,
  fecha DATETIME NOT NULL,
  diagnostico TEXT NOT NULL,
  tratamiento TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `horarios`
CREATE TABLE horarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  medico_id INT(11) NOT NULL,
  dia_semana INT(11) NOT NULL COMMENT '1=Domingo, 2=Lunes, ..., 7=Sábado',
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  estado TINYINT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `horarios_medicos`
CREATE TABLE horarios_medicos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  doctor_id INT(11) NOT NULL,
  dia_semana ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo') NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `horarios_personal`
CREATE TABLE horarios_personal (
  id INT(11) NOT NULL AUTO_INCREMENT,
  medico_id INT(11) DEFAULT NULL,
  dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') DEFAULT NULL,
  hora_inicio TIME DEFAULT NULL,
  hora_fin TIME DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `medicamentos`
CREATE TABLE medicamentos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  stock INT(11) DEFAULT 0,
  precio DECIMAL(10,2) DEFAULT NULL,
  estado ENUM('Disponible', 'Agotado', 'Descontinuado') DEFAULT 'Disponible',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `personal`
CREATE TABLE personal (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  tipo ENUM('Doctor', 'Enfermero', 'Farmaceutico', 'Otro') NOT NULL,
  dni VARCHAR(20) UNIQUE DEFAULT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  direccion TEXT DEFAULT NULL,
  estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  foto VARCHAR(255) DEFAULT NULL,
  fecha_nacimiento DATE DEFAULT NULL,
  genero ENUM('M', 'F', 'O') DEFAULT NULL,
  num_colegiado VARCHAR(50) DEFAULT NULL,
  cv TEXT DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `personal_especialidades`
CREATE TABLE personal_especialidades (
  personal_id INT(11) NOT NULL,
  especialidad_id INT(11) NOT NULL,
  fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (personal_id, especialidad_id),
  FOREIGN KEY (personal_id) REFERENCES personal(id) ON DELETE CASCADE,
  FOREIGN KEY (especialidad_id) REFERENCES especialidades(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `recetas`
CREATE TABLE recetas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  historial_id INT(11) NOT NULL,
  medicamento_id INT(11) NOT NULL,
  paciente_id INT(11) NOT NULL,
  medico_id INT(11) NOT NULL,
  dosis VARCHAR(100) DEFAULT NULL,
  frecuencia VARCHAR(100) DEFAULT NULL,
  duracion VARCHAR(100) DEFAULT NULL,
  fecha_emision DATE DEFAULT NULL,
  estado ENUM('Activa', 'Completada', 'Cancelada') DEFAULT 'Activa',
  PRIMARY KEY (id),
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY (medico_id) REFERENCES personal(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: servicios
CREATE TABLE servicios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  imagen VARCHAR(255) NOT NULL,
  enlace VARCHAR(255) NOT NULL,
  descripcion TEXT NOT NULL,
  duracion INT(11) NOT NULL COMMENT 'Duración en minutos',
  precio DECIMAL(10,2) NOT NULL,
  estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `usuario`
CREATE TABLE usuario (
  id INT(11) NOT NULL AUTO_INCREMENT,
  usuario VARCHAR(100) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  rol VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  rol ENUM('administrador', 'medico', 'enfermero', 'recepcionista', 'farmaceutico') NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

