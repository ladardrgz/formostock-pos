document.addEventListener('DOMContentLoaded', function() {
    // Botón de cancelar factura
    document.querySelectorAll('.cancel-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const facturaId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Serás redirigido a la sección de cancelación de factura.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `devolverFactura.php?id=${facturaId}`;
                }
            });
        });
    });

    // Botón de ver factura
    document.querySelectorAll('.view-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const facturaId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Quieres ver la factura?',
                text: 'Serás redirigido a la página de visualización de la factura.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'verFactura.php?id=' + facturaId;
                }
            });
        });
    });
});
// Función para confirmar la carga de un documento
function confirmarCarga(idFactura) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cargar un nuevo documento para esta factura?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cargar documento',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Si confirma, se abre el selector de archivo
            document.getElementById('fileInput_' + idFactura).click();
        }
    });
}

// Función para confirmar la descarga o visualización de un documento
function confirmarDescarga(url) {
    Swal.fire({
        title: '¿Deseas previsualizar el documento?',
        text: "Haz clic en confirmar para ver el archivo.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Sí, ver documento',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Si confirma, redirige a la URL del archivo
            window.location.href = url;
        }
    });
}
