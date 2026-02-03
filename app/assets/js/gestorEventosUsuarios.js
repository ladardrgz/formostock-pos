document.addEventListener('DOMContentLoaded', function() { 
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar este usuario después de eliminarlo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('deleteUserAction.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idUsuario': userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#6181e7', 
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                document.querySelector(`button[data-id="${userId}"]`).closest('tr').remove();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar el usuario.',
                                icon: 'error',
                                confirmButtonColor: '#6181e7',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la solicitud.',
                            icon: 'error',
                            confirmButtonColor: '#6181e7',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                }
            });
        });
    });

    // Botón de crear un usuario
    document.querySelectorAll('.add-button').forEach(button => {
        button.addEventListener('click', function() {
            Swal.fire({
                title: '¿Deseas agregar un nuevo usuario?',
                text: 'Serás redirigido a la página para la creación de usuarios.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'userRegister.php'; 
                }
            });
        });
    });

      // Botón de modificar un usuario
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Deseas modificar el usuario seleccionado?',
                text: 'Serás redirigido a la página para la actualización de información de los usuarios.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7', 
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'modificarUsuario.php?id=' + userId; 
                }
            });
        });
    });
});

