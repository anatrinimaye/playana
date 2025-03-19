

create DATABASE clinica;

use clinica;

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('Pendiente','Confirmada','Completada','Cancelada') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `paciente_id`, `medico_id`, `fecha_hora`, `motivo`, `estado`) VALUES
(2, 2, 2, '2025-03-13 00:41:00', 'consulta', 'Pendiente'),
(4, 2, 17, '2025-03-17 17:01:00', 'dddddd', 'Pendiente'),
(5, 4, 17, '2025-03-08 14:18:00', 'hghghghh', 'Pendiente'),
(6, 5, 2, '2025-03-13 14:19:00', 'jhgyugoip', 'Pendiente'),
(7, 2, 17, '2025-03-19 04:00:48', NULL, 'Confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos_pago`
--

CREATE TABLE `conceptos_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `tipo` enum('consulta','procedimiento','laboratorio','otro') NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `presion_arterial` varchar(20) DEFAULT NULL,
  `temperatura` decimal(4,1) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `motivo` text NOT NULL,
  `sintomas` text NOT NULL,
  `diagnostico` text NOT NULL,
  `tratamiento` text NOT NULL,
  `estado` enum('Activa','Completada','Cancelada') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_receta`
--

CREATE TABLE `detalles_receta` (
  `id` int(11) NOT NULL,
  `receta_id` int(11) DEFAULT NULL,
  `medicamento_id` int(11) DEFAULT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `frecuencia` varchar(100) DEFAULT NULL,
  `duracion` varchar(100) DEFAULT NULL,
  `instrucciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'infecioso', 'enfermedad de las manos', '2025-03-12 14:58:33'),
(2, 'dentistas', 'trata de dientes', '2025-03-12 15:55:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_solicitados`
--

CREATE TABLE `examenes_solicitados` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `notas` text DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL,
  `estado` enum('Pendiente','Realizado','Cancelado') DEFAULT 'Pendiente',
  `resultado` text DEFAULT NULL,
  `fecha_resultado` datetime DEFAULT NULL,
  `tipo_examen_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro de solicitudes de exámenes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historiales_clinicos`
--

CREATE TABLE `historiales_clinicos` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `fecha_consulta` datetime NOT NULL,
  `medico_id` int(11) NOT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historiales_clinicos`
--

INSERT INTO `historiales_clinicos` (`id`, `paciente_id`, `fecha_consulta`, `medico_id`, `diagnostico`, `tratamiento`, `observaciones`) VALUES
(1, 2, '2025-03-14 00:17:00', 2, 'gripe', '7 dias', 'se mejora'),
(4, 4, '2025-03-21 00:22:00', 17, 'alegias', 'dos veces', 'mejorando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_medico`
--

CREATE TABLE `historial_medico` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `diagnostico` text NOT NULL,
  `tratamiento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `dia_semana` int(11) NOT NULL COMMENT '1=Domingo, 2=Lunes, ..., 7=Sábado',
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` tinyint(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `medico_id`, `dia_semana`, `hora_inicio`, `hora_fin`, `estado`, `fecha_creacion`, `fecha_modificacion`) VALUES
(3, 17, 3, '20:28:00', '23:31:00', 1, '2025-03-15 19:27:25', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_bloqueados`
--

CREATE TABLE `horarios_bloqueados` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `motivo` text DEFAULT NULL,
  `creado_por` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_medicos`
--

CREATE TABLE `horarios_medicos` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_personal`
--

CREATE TABLE `horarios_personal` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` enum('Disponible','Agotado','Descontinuado') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicamentos`
--

INSERT INTO `medicamentos` (`id`, `nombre`, `descripcion`, `stock`, `precio`, `estado`) VALUES
(1, 'Hibuprofeno', 'para el delor', 12, 12000.00, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_stock`
--

CREATE TABLE `movimientos_stock` (
  `id` int(11) NOT NULL,
  `medicamento_id` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `muestras`
--

CREATE TABLE `muestras` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `codigo_barras` varchar(50) NOT NULL,
  `tipo_muestra` varchar(50) NOT NULL,
  `fecha_recoleccion` timestamp NOT NULL DEFAULT current_timestamp(),
  `recolectado_por` int(11) NOT NULL,
  `estado` enum('pendiente','procesada','descartada') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro de muestras recolectadas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','Otro') DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombre`, `apellidos`, `dni`, `fecha_nacimiento`, `genero`, `telefono`, `email`, `direccion`, `foto`, `estado`, `fecha_registro`) VALUES
(2, 'restituta', 'mba', '55555555', '2025-02-20', 'F', '222718208', 'tere@gmail.com', 'OYALA', NULL, 'Activo', '2025-02-26 23:40:53'),
(4, 'maria', 'nieves', '002.010912', '2025-02-11', 'F', '222718208', 'nieves@gmail.com', 'OYALA', NULL, 'Activo', '2025-02-26 23:43:50'),
(5, 'bernardo ramon', 'mba', '555555555', '2025-03-14', 'M', '666632255', 'bernardo@gmail.com', 'malabo', NULL, 'Activo', '2025-03-12 16:53:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `metodo_pago` enum('Efectivo','Tarjeta','Transferencia') DEFAULT NULL,
  `estado` enum('Pendiente','Completado','Cancelado') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `tipo` enum('Doctor','Enfermero','Farmaceutico','Otro') NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','O') DEFAULT NULL,
  `num_colegiado` varchar(50) DEFAULT NULL,
  `cv` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `nombre`, `apellidos`, `tipo`, `dni`, `telefono`, `email`, `direccion`, `estado`, `fecha_registro`, `foto`, `fecha_nacimiento`, `genero`, `num_colegiado`, `cv`) VALUES
(2, 'maria', 'nieves', 'Doctor', '002.010912', '222718208', 'nieves@gmail.com', 'OYALA', 'Activo', '2025-02-25 02:11:39', 'tren6.jpg', NULL, NULL, NULL, NULL),
(17, 'ramon', 'mba nguema', 'Doctor', '1111', '222145856', 'ramon@gmail.com', 'evinayong', 'Activo', '2025-03-15 18:43:53', '67d5bc594cc58_header.jpg', '2025-02-25', 'M', '005', '2anos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_especialidades`
--

CREATE TABLE `personal_especialidades` (
  `personal_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `historial_id` int(11) NOT NULL,
  `medicamento_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `frecuencia` varchar(100) DEFAULT NULL,
  `duracion` varchar(100) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `estado` enum('Activa','Completada','Cancelada') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `historial_id`, `medicamento_id`, `paciente_id`, `medico_id`, `dosis`, `frecuencia`, `duracion`, `fecha_emision`, `estado`) VALUES
(2, 4, 1, 2, 2, '5 compimidos', 'cada 8 horas', '10 dias', '2025-03-15', 'Activa'),
(3, 4, 1, 4, 17, '5 compimidos', '8 horas', '7dias', '2025-03-18', 'Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados_examenes`
--

CREATE TABLE `resultados_examenes` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `tecnico_id` int(11) NOT NULL,
  `fecha_resultado` timestamp NOT NULL DEFAULT current_timestamp(),
  `resultado_detalle` text NOT NULL,
  `observaciones` text DEFAULT NULL,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `estado_validacion` enum('preliminar','validado') DEFAULT 'preliminar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Resultados de los exámenes realizados';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados_laboratorio`
--

CREATE TABLE `resultados_laboratorio` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` datetime NOT NULL,
  `registrado_por` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala_espera`
--

CREATE TABLE `sala_espera` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `hora_llegada` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('esperando','en_consulta','atendido','no_presento') DEFAULT 'esperando',
  `tiempo_espera` int(11) DEFAULT NULL COMMENT 'Tiempo de espera en minutos',
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `duracion`, `precio`, `estado`) VALUES
(1, 'ortopedia', 'inflamacion en las zonas blandas del cuerpo tales como las articulaciones tanto de los pies como de las manos', 40, 50000.00, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_examenes`
--

CREATE TABLE `tipos_examenes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tiempo_estimado` int(11) NOT NULL COMMENT 'Tiempo estimado en minutos',
  `requiere_ayuno` tinyint(1) DEFAULT 0,
  `instrucciones_previas` text DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Catálogo de tipos de exámenes disponibles';

--
-- Volcado de datos para la tabla `tipos_examenes`
--

INSERT INTO `tipos_examenes` (`id`, `nombre`, `descripcion`, `precio`, `tiempo_estimado`, `requiere_ayuno`, `instrucciones_previas`, `estado`, `fecha_registro`, `fecha_actualizacion`) VALUES
(1, 'VIH', 'gghghgfhfhgfhfg', 50000.00, 2, 1, 'jmhfjkhkkjlkjlkly;i;', 'activo', '2025-03-12 18:47:39', '2025-03-12 18:47:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` enum('administrador','medico','enfermero','recepcionista','farmaceutico') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_creacion`, `rol`) VALUES
(8, 'Admin', 'admin@gmail.com', 'plp1221', '2025-03-18 23:25:37', 'administrador'),
(9, 'ana', 'ana@gmail.com', '$2y$10$qV1HtlMgjbdtb4p8XbQWwuqC5z.sKGm9G2v5ZaBJb8oBsaPQnZbLS', '2025-03-19 04:00:12', 'recepcionista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valores_referencia`
--

CREATE TABLE `valores_referencia` (
  `id` int(11) NOT NULL,
  `tipo_examen_id` int(11) NOT NULL,
  `parametro` varchar(100) NOT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `valor_minimo` decimal(10,3) DEFAULT NULL,
  `valor_maximo` decimal(10,3) DEFAULT NULL,
  `valor_texto` text DEFAULT NULL,
  `genero` enum('M','F','ambos') DEFAULT 'ambos',
  `edad_minima` int(11) DEFAULT NULL,
  `edad_maxima` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Valores de referencia para cada tipo de examen';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `idx_citas_fecha` (`fecha_hora`);

--
-- Indices de la tabla `conceptos_pago`
--
ALTER TABLE `conceptos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indices de la tabla `detalles_receta`
--
ALTER TABLE `detalles_receta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receta_id` (`receta_id`),
  ADD KEY `medicamento_id` (`medicamento_id`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examenes_solicitados`
--
ALTER TABLE `examenes_solicitados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consulta_id` (`consulta_id`),
  ADD KEY `idx_examenes_fecha` (`fecha_solicitud`),
  ADD KEY `idx_examenes_estado` (`estado`),
  ADD KEY `tipo_examen_id` (`tipo_examen_id`);

--
-- Indices de la tabla `historiales_clinicos`
--
ALTER TABLE `historiales_clinicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indices de la tabla `historial_medico`
--
ALTER TABLE `historial_medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `consulta_id` (`consulta_id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indices de la tabla `horarios_bloqueados`
--
ALTER TABLE `horarios_bloqueados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indices de la tabla `horarios_personal`
--
ALTER TABLE `horarios_personal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Indices de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_medicamentos_nombre` (`nombre`),
  ADD KEY `idx_medicamentos_estado` (`estado`);

--
-- Indices de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicamento_id` (`medicamento_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_movimientos_fecha` (`fecha_movimiento`);

--
-- Indices de la tabla `muestras`
--
ALTER TABLE `muestras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `recolectado_por` (`recolectado_por`),
  ADD KEY `idx_muestras_codigo` (`codigo_barras`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `idx_pacientes_dni` (`dni`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `personal_especialidades`
--
ALTER TABLE `personal_especialidades`
  ADD PRIMARY KEY (`personal_id`,`especialidad_id`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_id` (`historial_id`),
  ADD KEY `medicamento_id` (`medicamento_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `idx_recetas_estado` (`estado`);

--
-- Indices de la tabla `resultados_examenes`
--
ALTER TABLE `resultados_examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `tecnico_id` (`tecnico_id`),
  ADD KEY `idx_resultados_fecha` (`fecha_resultado`);

--
-- Indices de la tabla `resultados_laboratorio`
--
ALTER TABLE `resultados_laboratorio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indices de la tabla `sala_espera`
--
ALTER TABLE `sala_espera`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_examenes`
--
ALTER TABLE `tipos_examenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `valores_referencia`
--
ALTER TABLE `valores_referencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_examen_id` (`tipo_examen_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `conceptos_pago`
--
ALTER TABLE `conceptos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_receta`
--
ALTER TABLE `detalles_receta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `examenes_solicitados`
--
ALTER TABLE `examenes_solicitados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historiales_clinicos`
--
ALTER TABLE `historiales_clinicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historial_medico`
--
ALTER TABLE `historial_medico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_bloqueados`
--
ALTER TABLE `horarios_bloqueados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_personal`
--
ALTER TABLE `horarios_personal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `muestras`
--
ALTER TABLE `muestras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `resultados_examenes`
--
ALTER TABLE `resultados_examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resultados_laboratorio`
--
ALTER TABLE `resultados_laboratorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sala_espera`
--
ALTER TABLE `sala_espera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_examenes`
--
ALTER TABLE `tipos_examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `valores_referencia`
--
ALTER TABLE `valores_referencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `detalles_receta`
--
ALTER TABLE `detalles_receta`
  ADD CONSTRAINT `detalles_receta_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`),
  ADD CONSTRAINT `detalles_receta_ibfk_2` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`);

--
-- Filtros para la tabla `examenes_solicitados`
--
ALTER TABLE `examenes_solicitados`
  ADD CONSTRAINT `examenes_solicitados_ibfk_1` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`),
  ADD CONSTRAINT `examenes_solicitados_ibfk_2` FOREIGN KEY (`tipo_examen_id`) REFERENCES `tipos_examenes` (`id`);

--
-- Filtros para la tabla `historiales_clinicos`
--
ALTER TABLE `historiales_clinicos`
  ADD CONSTRAINT `historiales_clinicos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `historiales_clinicos_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `historial_medico`
--
ALTER TABLE `historial_medico`
  ADD CONSTRAINT `historial_medico_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `historial_medico_ibfk_2` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `horarios_bloqueados`
--
ALTER TABLE `horarios_bloqueados`
  ADD CONSTRAINT `horarios_bloqueados_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `horarios_bloqueados_ibfk_2` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  ADD CONSTRAINT `horarios_medicos_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `horarios_personal`
--
ALTER TABLE `horarios_personal`
  ADD CONSTRAINT `horarios_personal_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD CONSTRAINT `movimientos_stock_ibfk_1` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`),
  ADD CONSTRAINT `movimientos_stock_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `muestras`
--
ALTER TABLE `muestras`
  ADD CONSTRAINT `muestras_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes_solicitados` (`id`),
  ADD CONSTRAINT `muestras_ibfk_2` FOREIGN KEY (`recolectado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`);

--
-- Filtros para la tabla `personal_especialidades`
--
ALTER TABLE `personal_especialidades`
  ADD CONSTRAINT `personal_especialidades_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `personal_especialidades_ibfk_2` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`historial_id`) REFERENCES `historiales_clinicos` (`id`),
  ADD CONSTRAINT `recetas_ibfk_2` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`),
  ADD CONSTRAINT `recetas_ibfk_3` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `recetas_ibfk_4` FOREIGN KEY (`medico_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `resultados_examenes`
--
ALTER TABLE `resultados_examenes`
  ADD CONSTRAINT `resultados_examenes_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes_solicitados` (`id`),
  ADD CONSTRAINT `resultados_examenes_ibfk_2` FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `resultados_laboratorio`
--
ALTER TABLE `resultados_laboratorio`
  ADD CONSTRAINT `resultados_laboratorio_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes_solicitados` (`id`),
  ADD CONSTRAINT `resultados_laboratorio_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `sala_espera`
--
ALTER TABLE `sala_espera`
  ADD CONSTRAINT `sala_espera_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`);

--
-- Filtros para la tabla `valores_referencia`
--
ALTER TABLE `valores_referencia`
  ADD CONSTRAINT `valores_referencia_ibfk_1` FOREIGN KEY (`tipo_examen_id`) REFERENCES `tipos_examenes` (`id`);
COMMIT;

