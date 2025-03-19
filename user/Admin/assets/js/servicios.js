document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Cargado, iniciando carga de servicios...');
    loadServicios();
    setupSearch();
});

function loadServicios() {
    console.log('Iniciando carga de servicios...');
    const url = BASE_URL + 'modules/servicios/ajax_handler.php?action=getAll';
    console.log('URL de la petición:', url);

    fetch(url)
        .then(response => {
            console.log('Respuesta recibida:', response);
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            if (data.status === 'success') {
                const tbody = document.querySelector('#serviciosTable tbody');
                if (!tbody) {
                    console.error('No se encontró el elemento tbody');
                    return;
                }
                
                tbody.innerHTML = '';
                
                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">No hay servicios registrados</td>
                        </tr>
                    `;
                    return;
                }
                
                data.data.forEach(servicio => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${servicio.nombre}</td>
                            <td>${servicio.descripcion || '-'}</td>
                            <td>${servicio.duracion}</td>
                            <td>${formatCurrency(servicio.precio)}</td>
                            <td>
                                <span class="status-badge ${servicio.estado === 'Activo' ? 'active' : 'inactive'}">
                                    ${servicio.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editServicio(${servicio.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteServicio(${servicio.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                throw new Error(data.message || 'Error al cargar servicios');
            }
        })
        .catch(error => {
            console.error('Error en loadServicios:', error);
            showMessage('Error al cargar servicios: ' + error.message, 'error');
        });
}

function openModal(id = null) {
    const modal = document.getElementById('servicioModal');
    const form = document.getElementById('servicioForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Servicio';
        loadServicioData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Agregar Servicio';
        form.reset();
        document.getElementById('id').value = '';
    }
}

function closeModal() {
    const modal = document.getElementById('servicioModal');
    modal.style.display = 'none';
    document.getElementById('servicioForm').reset();
}

function loadServicioData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const servicio = data.data;
                document.getElementById('id').value = servicio.id;
                document.getElementById('nombre').value = servicio.nombre;
                document.getElementById('descripcion').value = servicio.descripcion;
                document.getElementById('duracion').value = servicio.duracion;
                document.getElementById('precio').value = servicio.precio;
                document.getElementById('estado').value = servicio.estado;
            }
        })
        .catch(error => showMessage('Error al cargar datos del servicio', 'error'));
}

document.getElementById('servicioForm').addEventListener('submit', function(e) {
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
            
            loadServicios();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error en la operación', 'error');
        console.error(error);
    });
});

function deleteServicio(id) {
    if (confirm('¿Está seguro de eliminar este servicio?')) {
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
                loadServicios();
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
        const rows = document.querySelectorAll('#serviciosTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
    }).format(amount);
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
    if (!container) {
        console.error('No se encontró el contenedor de mensajes');
        return;
    }

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