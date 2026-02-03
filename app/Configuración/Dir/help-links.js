// Archivo: js/help-links.js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.help-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = link.getAttribute('data-url');

            Swal.fire({
                title: '¿Deseas ser redirigido al formulario de registro?',
                text: "Serás redirigido al formulario de registro",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
