$(document).ready(function() {
    var table = $('#userTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });

    // Detecta doble clic para cambiar a un control de selección
    $('#userTable tbody').on('dblclick', '.editable-select', function() {
        var $cell = $(this);
        if ($cell.find('select').length === 0) {
            var options = JSON.parse($cell.attr('data-options'));
            var selectHtml = '<select class="form-control">';
            $.each(options, function(key, text) {
                selectHtml += `<option value="${key}" ${key == $cell.data('value') ? 'selected' : ''}>${text}</option>`;
            });
            selectHtml += '</select>';
            $cell.html(selectHtml);
            $cell.find('select').focus();
        }
    });

    // Actualizar el texto cuando el control select pierde el foco
    $('#userTable tbody').on('blur', 'select', function() {
        var $cell = $(this).closest('.editable-select');
        $cell.data('value', $(this).val());
        $cell.html($(this).find('option:selected').text());
        $cell.closest('tr').find('button.btn-success').css('background-color', '#33a633').prop('disabled', false);
    });

    // Habilitar botón guardar cuando hay cambios
    $('#userTable tbody').on('input change', 'td[contenteditable="true"], select', function() {
        $(this).closest('tr').find('button.btn-success').prop('disabled', false).css('background-color', '#33a633');
    });

    // Función para confirmar y eliminar usuario
    window.confirmDeleteUser = function(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, bórralo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controllers/userController.php?action=deleteUser',
                    type: 'POST',
                    data: { id_usuarios: userId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            Swal.fire('Eliminado!', 'El usuario ha sido eliminado.', 'success').then(function() {
location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'No se pudo eliminar el usuario.', 'error');
                        }
                    }
                });
            }
        });
    }
});

// Función global para editar usuario usando FormData
function editUser(id_usuarios) {
    var $row = $('tr[data-id="' + id_usuarios + '"]');
    var formData = new FormData();
    formData.append('id_usuarios', id_usuarios);
    formData.append('nombre', $row.find('td:eq(0)').text().trim());
    formData.append('correo', $row.find('td:eq(1)').text().trim());
    formData.append('puesto', $row.find('td:eq(2)').data('value'));
    formData.append('sucursal', $row.find('td:eq(3)').data('value'));
    formData.append('rol', $row.find('td:eq(4)').data('value'));
    formData.append('estado', $row.find('td:eq(5) select').val());

    $.ajax({
        url: '../controllers/userController.php?action=editUser',  // Incluye action en la URL
        type: 'POST',
        data: formData,
        processData: false,  // No procesar los datos
        contentType: false,  // No establecer el tipo de contenido (multipart/form-data)
        success: function(response) {
            var result = JSON.parse(response);
            Swal.fire(result.message, '', result.success ? 'success' : 'error').then(function() {
location.reload();
                            });
        },
        error: function() {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    });
}


// Función global para añadir usuario
function addUser(event) {
    event.preventDefault();
    var formData = new FormData(document.getElementById('addUserForm'));

    $.ajax({
        url: '../controllers/userController.php?action=addUser',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var result = JSON.parse(response);
            Swal.fire(result.message, '', result.success ? 'success' : 'error').then(function() {
location.reload();
$('#addUserModal').modal('hide');

                            });
            
        },
        error: function() {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    });
}