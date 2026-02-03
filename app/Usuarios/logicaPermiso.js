document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los checkbox de permisos
    const checkboxes = document.querySelectorAll('.switch-input');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const permisoId = this.dataset.permisoId;
            const rolId = this.dataset.rolId;
            const tienePermiso = this.checked; // true si está activado, false si está desactivado

            // Realiza la solicitud al servidor para actualizar el permiso
            fetch('actualizar_permiso.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    permiso_id: permisoId,
                    rol_id: rolId,
                    tiene_permiso: tienePermiso
                })
            })
            .then(response => response.json())
            .then(data => {
                // Muestra una alerta de éxito o error
                Swal.fire({
                    title: 'Éxito',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el permiso.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        });
    });
});
