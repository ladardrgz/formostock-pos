document.addEventListener('DOMContentLoaded', function () {
    // Botón de eliminación de producto
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const productId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar este producto después de eliminarlo!',
                icon: 'danger',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('EliminarProducto.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idProducto': productId
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
                                    // Eliminar el producto de la interfaz
                                    document.querySelector(`button[data-id="${productId}"]`).closest('tr').remove();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar el producto.',
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

    // Botón de modificar un producto
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const productId = this.closest('a').getAttribute('href').split('id=')[1];
            Swal.fire({
                title: '¿Deseas modificar el producto seleccionado?',
                text: 'Serás redirigido a la página para la actualización de información del producto.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la página de editar producto
                    window.location.href = 'modificarProducto.php?id=' + productId;
                }
            });
        });
    });

    // Botón de ver más información del producto
    document.querySelectorAll('.details-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const productId = this.closest('a').getAttribute('href').split('id=')[1];
            Swal.fire({
                title: '¿Deseas ver más información del producto?',
                text: 'Serás redirigido a la página de detalles del producto seleccionado.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'detallesProducto.php?id=' + productId;
                }
            });
        });
    });
});
    // Botón de aumentar stock
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.increase-stock-button').forEach(button => {
            button.addEventListener('click', async function () {
                const productoId = this.getAttribute('data-id');

                const { value: cantidad } = await Swal.fire({
                    title: 'Ingrese la cantidad a aumentar',
                    input: 'number',
                    inputLabel: 'Cantidad',
                    inputPlaceholder: 'Cantidad a aumentar',
                    inputAttributes: {
                        min: 1,
                        step: 1
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Aumentar stock',
                    cancelButtonText: 'Cancelar'
                });

                if (cantidad && cantidad > 0) {
                    window.location.href = `incrementarStock.php?producto_id=${productoId}&cantidad_aumentar=${cantidad}`;
                } else if (cantidad !== null) {
                    Swal.fire('Error', 'Por favor, ingrese una cantidad válida.', 'error');
                }
            });
        });
    });

