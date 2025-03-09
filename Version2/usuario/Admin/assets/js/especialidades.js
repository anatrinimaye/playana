// Variables globales
let especialidadesTable;
let searchTimeout;

// Funciones de inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando carga de especialidades...');
    
    // Verificar que BASE_URL esté definida
    if (typeof window.BASE_URL === 'undefined') {
        console.error('BASE_URL no está definida');
        return;
    }
    
    console.log('BASE_URL:', window.BASE_URL);
    loadEspecialidades();
    setupEventListeners();
});

function loadEspecialidades() {
    console.log('Iniciando carga de especialidades...');
    fetch('ajax_handler.php?action=getAll') // Asegúrate de que la ruta sea correcta
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tableBody = document.querySelector('#especialidadesTable tbody');
                tableBody.innerHTML = '';

                data.data.forEach(especialidad => {
                    const row = `
                        <tr>
                            <td>${especialidad.nombre}</td>
                            <td>${especialidad.descripcion || '-'}</td>
                            <td>${especialidad.medicos_asignados || 0}</td>
                            <td>
                                <button class="btn-edit" onclick="editEspecialidad(${especialidad.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteEspecialidad(${especialidad.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                console.error(data.message);
                showMessage('Error al cargar las especialidades', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar las especialidades', 'error');
        });
}

function setupForm() {
    document.getElementById('especialidadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('id').value;
        const submitButton = e.submitter;
        const action = submitButton.dataset.action;
        
        formData.append('action', id ? 'update' : 'create');

        fetch('ajax_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                
                if (action === 'save-return') {
                    closeModal();
                } else if (action === 'save-new') {
                    this.reset();
                    document.getElementById('id').value = '';
                }
                
                loadEspecialidades();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error en la operación', 'error');
        });
    });
}

function openModal(id = null) {
    const modal = document.getElementById('especialidadModal');
    const form = document.getElementById('especialidadForm');
    const title = document.getElementById('modalTitle');

    form.reset();
    if (id) {
        title.textContent = 'Editar Especialidad';
        loadEspecialidadData(id);
    } else {
        title.textContent = 'Nueva Especialidad';
        document.getElementById('id').value = '';
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('especialidadModal').style.display = 'none';
}

function loadEspecialidadData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const especialidad = data.data;
                document.getElementById('id').value = especialidad.id;
                document.getElementById('nombre').value = especialidad.nombre;
                document.getElementById('descripcion').value = especialidad.descripcion;
            }
        })
        .catch(error => showMessage('Error al cargar datos de la especialidad', 'error'));
}

function deleteEspecialidad(id) {
    if (confirm('¿Está seguro de eliminar esta especialidad?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        fetch('ajax_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                loadEspecialidades();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function verMedicos(id) {
    fetch(`ajax_handler.php?action=getMedicosAsignados&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let medicosHtml = '<ul class="medicos-list">';
                if (data.data.length > 0) {
                    data.data.forEach(medico => {
                        medicosHtml += `<li>${medico.nombre_completo}</li>`;
                    });
                } else {
                    medicosHtml += '<li>No hay médicos asignados a esta especialidad</li>';
                }
                medicosHtml += '</ul>';
                
                showModal('Médicos Asignados', medicosHtml);
            }
        })
        .catch(error => showMessage('Error al cargar médicos', 'error'));
}

function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#especialidadesTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
    container.className = type;
    container.textContent = message;
    container.style.display = 'block';
    setTimeout(() => {
        container.style.display = 'none';
    }, 3000);
}

function showModal(title, content) {
    const modalHtml = `
        <div class="modal" id="customModal">
            <div class="modal-content">
                <span class="close" onclick="closeCustomModal()">&times;</span>
                <h3>${title}</h3>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    document.getElementById('customModal').style.display = 'block';
}

function closeCustomModal() {
    const modal = document.getElementById('customModal');
    if (modal) {
        modal.remove();
    }
}

function editEspecialidad(id) {
    openModal(id);
}

function submitEspecialidadForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch(window.BASE_URL + 'modules/especialidades/ajax_handler.php?action=create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(data.message, 'success');
            loadEspecialidades(); // Cargar nuevamente las especialidades
            closeModal(); // Cerrar el modal
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error al crear la especialidad', 'error');
    });
}

function setupEventListeners() {
    // ... (resto de la función setupEventListeners)
}

function displayEspecialidades(data) {
    // ... (resto de la función displayEspecialidades)
} 