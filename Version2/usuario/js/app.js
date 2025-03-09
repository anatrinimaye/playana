document.addEventListener('DOMContentLoaded', function() {
    const app = document.getElementById('app');

    const components = [
        { name: 'Servicios', component: Servicios },
        { name: 'Personal', component: Personal },
        { name: 'Citas', component: Citas },
        { name: 'Horarios', component: Horarios },
        { name: 'Horarios Bloqueados', component: HorariosBloqueados }
    ];

    components.forEach(({ name, component }) => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `<h2>${name}</h2>`;
        card.appendChild(component());
        app.appendChild(card);
    });

    // Gráfico de Citas
    const citasCtx = document.getElementById('citasChart').getContext('2d');
    new Chart(citasCtx, {
        type: 'line',
        data: {
            labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
            datasets: [{
                label: 'Citas por día',
                data: [12, 19, 15, 17, 14],
                borderColor: '#2196f3',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(33, 150, 243, 0.1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Pacientes
    const pacientesCtx = document.getElementById('pacientesChart').getContext('2d');
    new Chart(pacientesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Nuevos', 'Recurrentes', 'De alta'],
            datasets: [{
                data: [30, 50, 20],
                backgroundColor: [
                    '#2196f3',
                    '#4caf50',
                    '#ff9800'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    loadEspecialidades(); // Asegúrate de que esta función esté disponible
});

function Servicios() {
    const div = document.createElement('div');
    div.innerHTML = '<p>Lista de servicios...</p>';
    return div;
}

function Personal() {
    const div = document.createElement('div');
    div.innerHTML = '<p>Lista de personal...</p>';
    return div;
}

function Citas() {
    const div = document.createElement('div');
    div.innerHTML = '<p>Lista de citas...</p>';
    return div;
}

function Horarios() {
    const div = document.createElement('div');
    div.innerHTML = '<p>Horarios disponibles...</p>';
    return div;
}

function HorariosBloqueados() {
    const div = document.createElement('div');
    div.innerHTML = '<p>Horarios bloqueados...</p>';
    return div;
} 