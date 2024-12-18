document.addEventListener('DOMContentLoaded', function () {
    // Función para manejar botones "Editar" de todos los modales
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');     // Obtiene el ID del registro
            const name = this.getAttribute('data-name'); // Obtiene el nombre del registro
            const type = this.getAttribute('data-type'); // Tipo de registro (sucursal, contacto, etc.)

            // Identifica el modal y actualiza sus campos dinámicamente
            switch (type) {
                case 'sucursal':
                    document.getElementById('editSucursalId').value = id;
                    document.getElementById('editSucursalName').value = name;
                    $('#editSucursalModal').modal('show'); // Abre el modal de edición
                    break;

                case 'contacto':
                    document.getElementById('editContactoId').value = id;
                    document.getElementById('editContactoName').value = name;
                    $('#editContactoModal').modal('show');
                    break;

                case 'estatus':
                    document.getElementById('editEstatusId').value = id;
                    document.getElementById('editEstatusName').value = name;
                    $('#editEstatusModal').modal('show');
                    break;

                case 'gerente':
                    document.getElementById('editGerenteId').value = id;
                    document.getElementById('editGerenteName').value = name;
                    $('#editGerenteModal').modal('show');
                    break;

                case 'periodo':
                    document.getElementById('editPeriodoId').value = id;
                    document.getElementById('editPeriodoName').value = name;
                    $('#editPeriodoModal').modal('show');
                    break;

                case 'negocio':
                    document.getElementById('editNegocioId').value = id;
                    document.getElementById('editNegocioName').value = name;
                    $('#editNegocioModal').modal('show');
                    break;

                default:
                    console.error('Tipo de modal no definido');
            }
        });
    });
});
