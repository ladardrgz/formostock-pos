document.addEventListener('DOMContentLoaded', function() {
    // Evento para el botón de ver detalles de devolución
    document.querySelectorAll('.view-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const devolucionId = this.getAttribute('data-id'); // Obtiene el ID de devolución

            Swal.fire({
                title: '¿Deseas ver los detalles de esta devolución?',
                text: 'Serás redirigido a la página de detalles de la devolución.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'verNotasPersonas.php?id=' + devolucionId;
                }
            });
        });
    });
});
