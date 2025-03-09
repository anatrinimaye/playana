// Variables globales
let personalTable;
let searchTimeout;

// Funciones de inicialización
document.addEventListener('DOMContentLoaded', function() {
    loadPersonal();
    setupEventListeners();
});

function setupEventListeners() {
    // Búsqueda
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchPersonal(e.target.value), 500);
    });

    // Formulario
    document.getElementById('personalForm').addEventListener('submit', submitForm);
}

// Funciones de carga de datos
function loadPersonal() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayPersonal(data.data);
            } else {
                showMessage(data.message || 'Error al cargar personal', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar personal', 'error');
        });
}

function displayPersonal(personal) {
    const tbody = document.querySelector('#personalTable tbody');
    tbody.innerHTML = '';
    
    personal.forEach(persona => {
        tbody.innerHTML += `
            <tr>
                <td>${persona.dni}</td>
                <td>${persona.nombre}</td>
                <td>${persona.apellidos}</td>
                <td>${persona.nombre_especialidad || 'N/A'}</td>
                <td>${persona.email || 'N/A'}</td>
                <td>${persona.telefono || 'N/A'}</td>
                <td>
                    <span class="status-badge ${persona.estado.toLowerCase()}">
                        ${persona.estado}
                    </span>
                </td>
                <td class="actions">
                    <button onclick="editPersonal(${persona.id})" class="btn-edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deletePersonal(${persona.id})" class="btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function loadEspecialidades() {
    fetch('ajax_handler.php?action=getEspecialidades')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('especialidad_id');
                select.innerHTML = '<option value="">Seleccione una especialidad</option>';
                data.data.forEach(especialidad => {
                    select.innerHTML += `<option value="${especialidad.id}">${especialidad.nombre}</option>`;
                });
            } else {
                console.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar especialidades:', error);
        });
}

// Funciones de formulario
function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const id = document.getElementById('id').value;
    
    fetch('ajax_handler.php?action=' + (id ? 'update' : 'create'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(data.message, 'success');
            closeModal();
            loadPersonal();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error en la operación', 'error');
    });
}

function editPersonal(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fillForm(data.data);
                openModal(true);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar datos', 'error');
        });
}

function deletePersonal(id) {
    if (confirm('¿Está seguro de eliminar este registro?')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('ajax_handler.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                loadPersonal();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al eliminar', 'error');
        });
    }
}

// Funciones auxiliares
function fillForm(data) {
    document.getElementById('id').value = data.id;
    document.getElementById('dni').value = data.dni;
    document.getElementById('nombre').value = data.nombre;
    document.getElementById('apellidos').value = data.apellidos;
    document.getElementById('especialidad_id').value = data.especialidad_id || '';
    document.getElementById('email').value = data.email || '';
    document.getElementById('telefono').value = data.telefono || '';
    document.getElementById('direccion').value = data.direccion || '';
    document.getElementById('estado').value = data.estado;
}

function openModal(isEdit = false) {
    const modal = document.getElementById('personalModal');
    const form = document.getElementById('personalForm');
    
    if (!isEdit) {
        document.getElementById('modalTitle').textContent = 'Nuevo Personal';
        form.reset();
        document.getElementById('id').value = '';
        loadEspecialidades(); // Cargar las especialidades cuando se abre el modal
    } else {
        document.getElementById('modalTitle').textContent = 'Editar Personal';
        // Cargar datos del personal a editar
    }
    
    modal.style.display = 'block'; // Asegúrate de que el modal se muestre
}

function closeModal() {
    document.getElementById('personalModal').style.display = 'none';
    document.getElementById('personalForm').reset();
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

function searchPersonal(query) {
    const rows = document.querySelectorAll('#personalTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
    });
} 