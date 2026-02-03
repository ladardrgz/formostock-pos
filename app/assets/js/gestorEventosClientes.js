document.addEventListener('DOMContentLoaded', function () {
    // Botón de eliminación de cliente
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const clientId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar este cliente después de eliminarlo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('eliminar_cliente.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idCliente': clientId
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
                                    // Opcional: eliminar el elemento de la interfaz
                                    document.querySelector(`button[data-id="${clientId}"]`).closest('tr').remove();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar el cliente.',
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

    // Botón de crear un cliente
    document.querySelectorAll('.add-button').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: '¿Deseas agregar un nuevo cliente?',
                text: 'Serás redirigido a la página para la creación de clientes.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'crearCliente.php';
                }
            });
        });
    });

    // Botón de modificar un cliente
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const clientId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Deseas modificar el cliente seleccionado?',
                text: 'Serás redirigido a la página para la actualización de información de los clientes.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'modificarCliente.php?id=' + clientId; 
                }
            });
        });
    });

    // Botón de historial de compras
    document.querySelectorAll('.history-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const clientId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Ver historial de compras?',
                text: 'Serás redirigido a la página del historial de compras del cliente.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'historial_cliente.php?id=' + clientId;
                }
            });
        });
    });
});
