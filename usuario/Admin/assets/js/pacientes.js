// Cargar datos cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    loadPacientes();
    setupSearch();
});

function loadPacientes() {
    fetch(BASE_URL + 'modules/pacientes/ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#pacientesTable tbody');
                tbody.innerHTML = '';
                
                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">No hay pacientes registrados</td>
                        </tr>
                    `;
                    return;
                }
                
                data.data.forEach(paciente => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${paciente.dni}</td>
                            <td>${paciente.nombre}</td>
                            <td>${paciente.apellidos}</td>
                            <td>${paciente.telefono || 'N/A'}</td>
                            <td>${paciente.email || 'N/A'}</td>
                            <td>
                                <span class="status-badge ${paciente.estado === 'Activo' ? 'active' : 'inactive'}">
                                    ${paciente.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editPaciente(${paciente.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePaciente(${paciente.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                console.error('Error al cargar pacientes:', data.message);
                showMessage(data.message || 'Error al cargar pacientes', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar pacientes', 'error');
        });
}

function openModal(id = null) {
    const modal = document.getElementById('pacienteModal');
    const form = document.getElementById('pacienteForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Paciente';
        loadPacienteData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Agregar Paciente';
        form.reset();
        document.getElementById('id').value = '';
    }
}

function closeModal() {
    const modal = document.getElementById('pacienteModal');
    modal.style.display = 'none';
    document.getElementById('pacienteForm').reset();
}

function loadPacienteData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const paciente = data.data;
                document.getElementById('id').value = paciente.id;
                document.getElementById('dni').value = paciente.dni;
                document.getElementById('nombre').value = paciente.nombre;
                document.getElementById('apellidos').value = paciente.apellidos;
                document.getElementById('fecha_nacimiento').value = paciente.fecha_nacimiento;
                document.getElementById('genero').value = paciente.genero;
                document.getElementById('direccion').value = paciente.direccion;
                document.getElementById('telefono').value = paciente.telefono;
                document.getElementById('email').value = paciente.email;
                document.getElementById('grupo_sanguineo').value = paciente.grupo_sanguineo;
                document.getElementById('alergias').value = paciente.alergias;
                document.getElementById('estado').value = paciente.estado;
            }
        })
        .catch(error => showMessage('Error al cargar datos del paciente', 'error'));
}

document.getElementById('pacienteForm').addEventListener('submit', function(e) {
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
            
            loadPacientes();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error en la operación', 'error');
        console.error(error);
    });
});

function deletePaciente(id) {
    if (confirm('¿Está seguro de eliminar este paciente?')) {
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
                loadPacientes();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#pacientesTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    container.innerHTML = '';
    container.appendChild(alert);
    
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => container.innerHTML = '', 300);
    }, 3000);
} 