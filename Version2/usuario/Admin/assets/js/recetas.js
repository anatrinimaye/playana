document.addEventListener('DOMContentLoaded', function() {
    loadRecetas();
    loadPacientes();
    loadMedicos();
    loadMedicamentos();
    setupSearch();
    setupFilters();
});

function loadRecetas() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#recetasTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(receta => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${receta.numero_receta}</td>
                            <td>${formatDateTime(receta.fecha)}</td>
                            <td>${receta.nombre_paciente}</td>
                            <td>${receta.nombre_medico}</td>
                            <td>${receta.diagnostico.substring(0, 50)}...</td>
                            <td>
                                <span class="status-badge ${receta.estado.toLowerCase()}">
                                    ${receta.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editReceta(${receta.id})" class="btn-edit"
                                        ${receta.estado === 'Anulada' ? 'disabled' : ''}>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteReceta(${receta.id})" class="btn-delete"
                                        ${receta.estado === 'Anulada' ? 'disabled' : ''}>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printReceta(${receta.id})" class="btn-print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar recetas', 'error'));
}

function loadPacientes() {
    fetch('ajax_handler.php?action=getPacientes')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('paciente_id');
                select.innerHTML = '<option value="">Seleccione un paciente</option>';
                
                data.data.forEach(paciente => {
                    select.innerHTML += `
                        <option value="${paciente.id}">${paciente.nombre_completo}</option>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar pacientes', 'error'));
}

function loadMedicos() {
    fetch('ajax_handler.php?action=getMedicos')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('medico_id');
                const filterSelect = document.getElementById('filterMedico');
                const options = '<option value="">Seleccione un médico</option>' +
                    data.data.map(medico => 
                        `<option value="${medico.id_personal}">${medico.nombre_completo}</option>`
                    ).join('');
                
                select.innerHTML = options;
                filterSelect.innerHTML = '<option value="">Todos</option>' + options;
            }
        })
        .catch(error => showMessage('Error al cargar médicos', 'error'));
}

function loadMedicamentos() {
    fetch('ajax_handler.php?action=getMedicamentos')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.medicamentosData = data.data;
            }
        })
        .catch(error => showMessage('Error al cargar medicamentos', 'error'));
}

function agregarMedicamento() {
    const medicamentosList = document.getElementById('medicamentosList');
    const index = medicamentosList.children.length;
    
    const medicamentoHtml = `
        <div class="medicamento-item" data-index="${index}">
            <div class="form-group">
                <label>Medicamento:</label>
                <select name="medicamentos[${index}][medicamento_id]" required>
                    <option value="">Seleccione un medicamento</option>
                    ${window.medicamentosData.map(med => 
                        `<option value="${med.id}">${med.nombre}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="form-group">
                <label>Dosis:</label>
                <input type="text" name="medicamentos[${index}][dosis]" required>
            </div>
            <div class="form-group">
                <label>Frecuencia:</label>
                <input type="text" name="medicamentos[${index}][frecuencia]" required>
            </div>
            <div class="form-group">
                <label>Duración:</label>
                <input type="text" name="medicamentos[${index}][duracion]" required>
            </div>
            <div class="form-group">
                <label>Indicaciones:</label>
                <textarea name="medicamentos[${index}][indicaciones]" rows="2"></textarea>
            </div>
            <button type="button" class="btn-remove" onclick="removeMedicamento(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    medicamentosList.insertAdjacentHTML('beforeend', medicamentoHtml);
}

function removeMedicamento(button) {
    button.closest('.medicamento-item').remove();
}

function openModal(id = null) {
    const modal = document.getElementById('recetaModal');
    const form = document.getElementById('recetaForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Receta';
        loadRecetaData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Nueva Receta';
        form.reset();
        document.getElementById('id').value = '';
        document.getElementById('medicamentosList').innerHTML = '';
        agregarMedicamento();
    }
}

function closeModal() {
    const modal = document.getElementById('recetaModal');
    modal.style.display = 'none';
    document.getElementById('recetaForm').reset();
    document.getElementById('medicamentosList').innerHTML = '';
}

function loadRecetaData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const receta = data.data;
                document.getElementById('id').value = receta.id;
                document.getElementById('paciente_id').value = receta.paciente_id;
                document.getElementById('medico_id').value = receta.medico_id;
                document.getElementById('diagnostico').value = receta.diagnostico;
                document.getElementById('indicaciones').value = receta.indicaciones;
                document.getElementById('estado').value = receta.estado;

                // Cargar medicamentos
                document.getElementById('medicamentosList').innerHTML = '';
                receta.medicamentos.forEach((med, index) => {
                    agregarMedicamento();
                    const item = document.querySelector(`.medicamento-item[data-index="${index}"]`);
                    item.querySelector('select').value = med.medicamento_id;
                    item.querySelector('input[name$="[dosis]"]').value = med.dosis;
                    item.querySelector('input[name$="[frecuencia]"]').value = med.frecuencia;
                    item.querySelector('input[name$="[duracion]"]').value = med.duracion;
                    item.querySelector('textarea').value = med.indicaciones;
                });
            }
        })
        .catch(error => showMessage('Error al cargar datos de la receta', 'error'));
}

document.getElementById('recetaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = document.getElementById('id').value;
    const submitButton = e.submitter;
    const action = submitButton.dataset.action;
    
    // Convertir medicamentos a JSON
    const medicamentos = [];
    document.querySelectorAll('.medicamento-item').forEach(item => {
        medicamentos.push({
            medicamento_id: item.querySelector('select').value,
            dosis: item.querySelector('input[name$="[dosis]"]').value,
            frecuencia: item.querySelector('input[name$="[frecuencia]"]').value,
            duracion: item.querySelector('input[name$="[duracion]"]').value,
            indicaciones: item.querySelector('textarea').value
        });
    });
    
    formData.append('action', id ? 'update' : 'create');
    formData.append('medicamentos', JSON.stringify(medicamentos));

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
                document.getElementById('medicamentosList').innerHTML = '';
                agregarMedicamento();
            }
            
            loadRecetas();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error en la operación', 'error');
        console.error(error);
    });
});

function deleteReceta(id) {
    if (confirm('¿Está seguro de eliminar esta receta?')) {
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
                loadRecetas();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function setupFilters() {
    const filterFecha = document.getElementById('filterFecha');
    const filterMedico = document.getElementById('filterMedico');
    const filterEstado = document.getElementById('filterEstado');

    [filterFecha, filterMedico, filterEstado].forEach(filter => {
        filter.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const fecha = document.getElementById('filterFecha').value;
    const medico = document.getElementById('filterMedico').value;
    const estado = document.getElementById('filterEstado').value;

    let url = 'ajax_handler.php?action=filtrar';
    if (fecha) url += `&fecha=${fecha}`;
    if (medico) url += `&medico=${medico}`;
    if (estado) url += `&estado=${estado}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#recetasTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(receta => {
                    // Mismo código de renderizado que en loadRecetas()
                    tbody.innerHTML += `
                        <tr>
                            <td>${receta.numero_receta}</td>
                            <td>${formatDateTime(receta.fecha)}</td>
                            <td>${receta.nombre_paciente}</td>
                            <td>${receta.nombre_medico}</td>
                            <td>${receta.diagnostico.substring(0, 50)}...</td>
                            <td>
                                <span class="status-badge ${receta.estado.toLowerCase()}">
                                    ${receta.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editReceta(${receta.id})" class="btn-edit"
                                        ${receta.estado === 'Anulada' ? 'disabled' : ''}>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteReceta(${receta.id})" class="btn-delete"
                                        ${receta.estado === 'Anulada' ? 'disabled' : ''}>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printReceta(${receta.id})" class="btn-print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al aplicar filtros', 'error'));
}

function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#recetasTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function printReceta(id) {
    window.open(`print_receta.php?id=${id}`, '_blank');
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