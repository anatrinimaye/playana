let usuariosTable;
let usuarioModal;

document.addEventListener('DOMContentLoaded', function() {
    usuarioModal = new bootstrap.Modal(document.getElementById('usuarioModal'));
    
    // Inicializar DataTable
    usuariosTable = $('#usuariosTable').DataTable({
        ajax: {
            url: 'ajax_handler.php?action=listar',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'username' },
            { data: 'nombre' },
            { data: 'rol' },
            { 
                data: 'estado',
                render: function(data) {
                    return `<span class="badge ${data === 'Activo' ? 'bg-success' : 'bg-danger'}">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-primary" onclick="editarUsuario(${data.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${data.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        }
    });
});

function openModal(isEdit = false) {
    document.getElementById('usuarioForm').reset();
    document.getElementById('usuario_id').value = '';
    document.getElementById('modalTitle').textContent = isEdit ? 'Editar Usuario' : 'Nuevo Usuario';
    usuarioModal.show();
}

function editarUsuario(id) {
    fetch(`ajax_handler.php?action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('usuario_id').value = data.id;
            document.getElementById('username').value = data.username;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('rol').value = data.rol;
            document.getElementById('estado').value = data.estado;
            
            document.getElementById('modalTitle').textContent = 'Editar Usuario';
            usuarioModal.show();
        });
}

function guardarUsuario() {
    const formData = new FormData(document.getElementById('usuarioForm'));
    
    fetch('ajax_handler.php?action=guardar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            usuarioModal.hide();
            usuariosTable.ajax.reload();
            alert('Usuario guardado exitosamente');
        } else {
            alert('Error al guardar el usuario: ' + data.message);
        }
    });
}

function eliminarUsuario(id) {
    if (confirm('¿Está seguro de eliminar este usuario?')) {
        fetch(`ajax_handler.php?action=eliminar&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    usuariosTable.ajax.reload();
                    alert('Usuario eliminado exitosamente');
                } else {
                    alert('Error al eliminar el usuario');
                }
            });
    }
}

function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#usuariosTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '';
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