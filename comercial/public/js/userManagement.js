<script> 
    // Manejar el envío del formulario de registro de usuario
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío del formulario tradicional
        const form = this;
        
        // Enviar datos al controlador usando Fetch API
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Usuario registrado exitosamente
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Usuario registrado exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $('#addUserModal').modal('hide'); // Cerrar el modal
                    location.reload(); // Recargar la página para ver los cambios
                });
            } else {
                // Error al registrar usuario
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar usuario: ' + data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al procesar la solicitud.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });
</script>
