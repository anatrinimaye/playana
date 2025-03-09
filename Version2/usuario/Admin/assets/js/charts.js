document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos de PHP mediante AJAX
    fetch('get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            createCharts(data);
        });
});

function createCharts(data) {
    // Gráfico de Citas
    const citasCtx = document.getElementById('citasChart').getContext('2d');
    new Chart(citasCtx, {
        type: 'line',
        data: {
            labels: data.citas.labels,
            datasets: [{
                label: 'Citas por día',
                data: data.citas.data,
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
            labels: data.pacientes.labels,
            datasets: [{
                data: data.pacientes.data,
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
} 