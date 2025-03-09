document.addEventListener('DOMContentLoaded', function() {
    loadPagos();
    loadPacientes();
    setupSearch();
    setupFilters();
});

function loadPagos() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#pagosTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(pago => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${pago.numero_recibo}</td>
                            <td>${formatDateTime(pago.fecha)}</td>
                            <td>${pago.nombre_paciente}</td>
                            <td>${pago.concepto}</td>
                            <td>${formatCurrency(pago.monto)}</td>
                            <td>${pago.metodo_pago}</td>
                            <td>
                                <span class="status-badge ${pago.estado.toLowerCase()}">
                                    ${pago.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editPago(${pago.id})" class="btn-edit" 
                                        ${pago.estado === 'Pagado' ? 'disabled' : ''}>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePago(${pago.id})" class="btn-delete"
                                        ${pago.estado === 'Pagado' ? 'disabled' : ''}>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printRecibo(${pago.id})" class="btn-print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar pagos', 'error'));
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

function openModal(id = null) {
    const modal = document.getElementById('pagoModal');
    const form = document.getElementById('pagoForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Pago';
        loadPagoData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Registrar Pago';
        form.reset();
        document.getElementById('id').value = '';
        // Establecer fecha actual por defecto
        document.getElementById('fecha').value = new Date().toISOString().slice(0, 16);
    }
}

function closeModal() {
    const modal = document.getElementById('pagoModal');
    modal.style.display = 'none';
    document.getElementById('pagoForm').reset();
}

function loadPagoData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const pago = data.data;
                document.getElementById('id').value = pago.id;
                document.getElementById('paciente_id').value = pago.paciente_id;
                document.getElementById('concepto').value = pago.concepto;
                document.getElementById('monto').value = pago.monto;
                document.getElementById('metodo_pago').value = pago.metodo_pago;
                document.getElementById('fecha').value = formatDateTimeLocal(pago.fecha);
                document.getElementById('estado').value = pago.estado;
                document.getElementById('observaciones').value = pago.observaciones;
            }
        })
        .catch(error => showMessage('Error al cargar datos del pago', 'error'));
}

document.getElementById('pagoForm').addEventListener('submit', function(e) {
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
                document.getElementById('fecha').value = new Date().toISOString().slice(0, 16);
            }
            
            loadPagos();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error en la operación', 'error');
        console.error(error);
    });
});

function deletePago(id) {
    if (confirm('¿Está seguro de eliminar este pago?')) {
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
                loadPagos();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function setupFilters() {
    const filterFecha = document.getElementById('filterFecha');
    const filterEstado = document.getElementById('filterEstado');

    [filterFecha, filterEstado].forEach(filter => {
        filter.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const fecha = document.getElementById('filterFecha').value;
    const estado = document.getElementById('filterEstado').value;

    let url = 'ajax_handler.php?action=filtrar';
    if (fecha) url += `&fecha=${fecha}`;
    if (estado) url += `&estado=${estado}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#pagosTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(pago => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${pago.numero_recibo}</td>
                            <td>${formatDateTime(pago.fecha)}</td>
                            <td>${pago.nombre_paciente}</td>
                            <td>${pago.concepto}</td>
                            <td>${formatCurrency(pago.monto)}</td>
                            <td>${pago.metodo_pago}</td>
                            <td>
                                <span class="status-badge ${pago.estado.toLowerCase()}">
                                    ${pago.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editPago(${pago.id})" class="btn-edit"
                                        ${pago.estado === 'Pagado' ? 'disabled' : ''}>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePago(${pago.id})" class="btn-delete"
                                        ${pago.estado === 'Pagado' ? 'disabled' : ''}>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printRecibo(${pago.id})" class="btn-print">
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
        const rows = document.querySelectorAll('#pagosTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function printRecibo(id) {
    // Implementar la lógica de impresión del recibo
    window.open(`print_recibo.php?id=${id}`, '_blank');
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

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
    }).format(amount);
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