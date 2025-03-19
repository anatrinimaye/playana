document.addEventListener('DOMContentLoaded', function() {
    loadMedicamentos();
    setupSearch();
    setupFilters();
    setupForm();
});

function loadMedicamentos() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#medicamentosTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(medicamento => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${medicamento.codigo}</td>
                            <td>${medicamento.nombre}</td>
                            <td>${medicamento.categoria}</td>
                            <td>${medicamento.descripcion || '-'}</td>
                            <td>
                                <span class="stock-badge ${getStockClass(medicamento.stock)}">
                                    ${medicamento.stock}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge ${medicamento.estado.toLowerCase()}">
                                    ${medicamento.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editMedicamento(${medicamento.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteMedicamento(${medicamento.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="adjustStock(${medicamento.id})" class="btn-stock">
                                    <i class="fas fa-boxes"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar medicamentos', 'error'));
}

function getStockClass(stock) {
    if (stock <= 0) return 'agotado';
    if (stock <= 10) return 'bajo';
    return 'normal';
}

function setupForm() {
    document.getElementById('medicamentoForm').addEventListener('submit', function(e) {
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
                
                loadMedicamentos();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Error en la operación', 'error');
            console.error(error);
        });
    });
}

function openModal(id = null) {
    const modal = document.getElementById('medicamentoModal');
    const form = document.getElementById('medicamentoForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Medicamento';
        loadMedicamentoData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Nuevo Medicamento';
        form.reset();
        document.getElementById('id').value = '';
    }
}

function closeModal() {
    const modal = document.getElementById('medicamentoModal');
    modal.style.display = 'none';
    document.getElementById('medicamentoForm').reset();
}

function loadMedicamentoData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const medicamento = data.data;
                document.getElementById('id').value = medicamento.id;
                document.getElementById('codigo').value = medicamento.codigo;
                document.getElementById('nombre').value = medicamento.nombre;
                document.getElementById('categoria').value = medicamento.categoria;
                document.getElementById('descripcion').value = medicamento.descripcion;
                document.getElementById('stock').value = medicamento.stock;
                document.getElementById('estado').value = medicamento.estado;
            }
        })
        .catch(error => showMessage('Error al cargar datos del medicamento', 'error'));
}

function deleteMedicamento(id) {
    if (confirm('¿Está seguro de eliminar este medicamento?')) {
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
                loadMedicamentos();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function adjustStock(id) {
    const cantidad = prompt('Ingrese la cantidad a ajustar (use números negativos para reducir stock):');
    if (cantidad === null) return;
    
    const formData = new FormData();
    formData.append('action', 'updateStock');
    formData.append('id', id);
    formData.append('cantidad', cantidad);

    fetch('ajax_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(data.message, 'success');
            loadMedicamentos();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => showMessage('Error al ajustar stock', 'error'));
}

function setupFilters() {
    const filterCategoria = document.getElementById('filterCategoria');
    const filterEstado = document.getElementById('filterEstado');

    [filterCategoria, filterEstado].forEach(filter => {
        filter.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const categoria = document.getElementById('filterCategoria').value;
    const estado = document.getElementById('filterEstado').value;

    let url = 'ajax_handler.php?action=filtrar';
    if (categoria) url += `&categoria=${categoria}`;
    if (estado) url += `&estado=${estado}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#medicamentosTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(medicamento => {
                    // Mismo código de renderizado que en loadMedicamentos()
                    tbody.innerHTML += `
                        <tr>
                            <td>${medicamento.codigo}</td>
                            <td>${medicamento.nombre}</td>
                            <td>${medicamento.categoria}</td>
                            <td>${medicamento.descripcion || '-'}</td>
                            <td>
                                <span class="stock-badge ${getStockClass(medicamento.stock)}">
                                    ${medicamento.stock}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge ${medicamento.estado.toLowerCase()}">
                                    ${medicamento.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editMedicamento(${medicamento.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteMedicamento(${medicamento.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="adjustStock(${medicamento.id})" class="btn-stock">
                                    <i class="fas fa-boxes"></i>
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
        const rows = document.querySelectorAll('#medicamentosTable tbody tr');
        
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