document.addEventListener('DOMContentLoaded', function() {
    loadHorariosBloqueados();
    loadMedicos();
    setupSearch();
});

function loadHorariosBloqueados() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#bloqueoTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(bloqueo => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${bloqueo.nombre_medico}</td>
                            <td>${formatDateTime(bloqueo.fecha_inicio)}</td>
                            <td>${formatDateTime(bloqueo.fecha_fin)}</td>
                            <td>${bloqueo.motivo}</td>
                            <td class="actions">
                                <button onclick="editBloqueo(${bloqueo.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteBloqueo(${bloqueo.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar horarios bloqueados', 'error'));
}

function loadMedicos() {
    fetch('ajax_handler.php?action=getMedicos')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('medico_id');
                select.innerHTML = '<option value="">Seleccione un médico</option>';
                
                data.data.forEach(medico => {
                    select.innerHTML += `
                        <option value="${medico.id_personal}">${medico.nombre_completo}</option>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar médicos', 'error'));
}

function openModal(id = null) {
    const modal = document.getElementById('bloqueoModal');
    const form = document.getElementById('bloqueoForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Bloqueo';
        loadBloqueoData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Agregar Bloqueo';
        form.reset();
        document.getElementById('id').value = '';
    }
}

function closeModal() {
    const modal = document.getElementById('bloqueoModal');
    modal.style.display = 'none';
    document.getElementById('bloqueoForm').reset();
}

function loadBloqueoData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const bloqueo = data.data;
                document.getElementById('id').value = bloqueo.id;
                document.getElementById('medico_id').value = bloqueo.medico_id;
                document.getElementById('fecha_inicio').value = formatDateTimeLocal(bloqueo.fecha_inicio);
                document.getElementById('fecha_fin').value = formatDateTimeLocal(bloqueo.fecha_fin);
                document.getElementById('motivo').value = bloqueo.motivo;
            }
        })
        .catch(error => showMessage('Error al cargar datos del bloqueo', 'error'));
}

document.getElementById('bloqueoForm').addEventListener('submit', function(e) {
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
            
            loadHorariosBloqueados();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error en la operación', 'error');
        console.error(error);
    });
});

function deleteBloqueo(id) {
    if (confirm('¿Está seguro de eliminar este bloqueo?')) {
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
                loadHorariosBloqueados();
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
        const rows = document.querySelectorAll('#bloqueoTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateTimeLocal(dateTimeStr) {
    return dateTimeStr.replace(' ', 'T');
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