function iniciarFactura() {
    fetch('inicializarFac.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Factura iniciada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                });
            } else {
                Swal.fire({
                    title: 'Advertencia',
                    text: data.message,
                    icon: 'warning',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                });
            }
        })
        .catch(error => {
            console.error('Error al iniciar la factura:', error);
            Swal.fire({
                title: 'Error',
                text: 'Se produjo un error al iniciar la factura.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            });
        });
}
