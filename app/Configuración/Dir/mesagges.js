// Archivo: js/messages.js
document.addEventListener('DOMContentLoaded', () => {
    // Mostrar mensaje de la sesión si existe
    if (sessionMessage) {
        Swal.fire({
            icon: sessionMessage.type,
            title: sessionMessage.title,
            text: sessionMessage.text,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#67f120'
        });
    }
});
