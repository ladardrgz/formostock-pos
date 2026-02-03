// Manejo de opciones y notificaciones con SweetAlert en la gestión de sucursales

document.addEventListener('DOMContentLoaded', function() {
    // Botón de eliminación de sucursal
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const sucursalId = this.getAttribute('data-id');
            // Estilo de botón eliminación de sucursal
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar esta sucursal después de eliminarla!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('eliminarSucursal.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idSucursal': sucursalId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Eliminada!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Opcional: eliminar el elemento de la interfaz
                                document.querySelector(`button[data-id="${sucursalId}"]`).closest('tr').remove();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar la sucursal.',
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

    // Botón de crear una sucursal
    document.querySelectorAll('.add-button').forEach(button => {
        button.addEventListener('click', function() {
            // Estilo de botón de crear una sucursal
            Swal.fire({
                title: '¿Deseas agregar una nueva sucursal?',
                text: 'Serás redirigido a la página para la creación de sucursales.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#9b59b6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, ir a sección crear una sucursal',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'registro_sucursal.php'; // Redirigir a la página de agregar sucursal
                }
            });
        });
    });

    // Botón de modificar una sucursal
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const sucursalId = this.getAttribute('data-id');
            // Estilo de botón de modificar una sucursal
            Swal.fire({
                title: '¿Deseas modificar la sucursal seleccionada?',
                text: 'Serás redirigido a la página para la actualización de información de la sucursal.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#4bc2ef',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Sí, ir a sección modificar sucursal',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'edicionSucursal.php?id=' + sucursalId; 
                    // Redirigir a la página de editar sucursal
                }
            });
        });
    });
});
