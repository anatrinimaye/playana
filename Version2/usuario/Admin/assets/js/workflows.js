// Definición de roles y sus permisos
$roles = [
    'admin' => [
        'nombre' => 'Administrador',
        'permisos' => 'total'
    ],
    'doctor' => [
        'nombre' => 'Médico',
        'permisos' => ['consultas', 'historiales', 'recetas']
    ],
    'recepcionista' => [
        'nombre' => 'Recepcionista',
        'permisos' => ['citas', 'pacientes', 'pagos']
    ],
    'enfermera' => [
        'nombre' => 'Enfermera',
        'permisos' => ['signos_vitales', 'historiales']
    ],
    'farmaceutico' => [
        'nombre' => 'Farmacéutico',
        'permisos' => ['medicamentos', 'inventario']
    ]
];

// Flujo de trabajo para citas
function nuevaCita() {
    const workflow = {
        paso1: {
            titulo: 'Seleccionar Paciente',
            accion: seleccionarPaciente
        },
        paso2: {
            titulo: 'Seleccionar Especialidad',
            accion: seleccionarEspecialidad
        },
        paso3: {
            titulo: 'Seleccionar Médico',
            accion: seleccionarMedico
        },
        paso4: {
            titulo: 'Seleccionar Fecha y Hora',
            accion: seleccionarHorario
        },
        paso5: {
            titulo: 'Confirmar Cita',
            accion: confirmarCita
        }
    };
    
    iniciarWorkflow(workflow);
}

// Flujo de trabajo para consultas médicas
function iniciarConsulta(citaId) {
    const workflow = {
        paso1: {
            titulo: 'Signos Vitales',
            accion: registrarSignosVitales
        },
        paso2: {
            titulo: 'Consulta Médica',
            accion: realizarConsulta
        },
        paso3: {
            titulo: 'Diagnóstico y Tratamiento',
            accion: registrarDiagnostico
        },
        paso4: {
            titulo: 'Receta Médica',
            accion: generarReceta
        },
        paso5: {
            titulo: 'Próxima Cita',
            accion: programarSeguimiento
        }
    };
    
    iniciarWorkflow(workflow);
} 