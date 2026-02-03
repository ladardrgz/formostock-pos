document.addEventListener('DOMContentLoaded', function() {
    // Botón de eliminación de proveedor
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const providerId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar este proveedor después de eliminarlo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', 
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('EliminarProveedor.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idProveedor': providerId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Eliminar el proveedor de la interfaz
                                document.querySelector(`button[data-id="${providerId}"]`).closest('tr').remove();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar el proveedor.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la solicitud.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                }
            });
        });
    });

    // Botón de modificar un proveedor
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const providerId = this.closest('a').getAttribute('href').split('id=')[1];
            Swal.fire({
                title: '¿Deseas modificar el proveedor seleccionado?',
                text: 'Serás redirigido a la página para la actualización de información del proveedor.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#4bc2ef',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, ir a sección modificar proveedor',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la página de editar proveedor
                    window.location.href = 'modificarProveedor.php?id=' + providerId;
                }
            });
        });
    });
});
