document.addEventListener('DOMContentLoaded', function() {
    const fechaNacimientoInput = document.getElementById('fechaNacimiento');
    const form = document.querySelector('form');

    // Función para calcular la edad y validar la fecha
    function validateDate() {
        const inputDate = new Date(fechaNacimientoInput.value);
        const today = new Date();
        const age = today.getFullYear() - inputDate.getFullYear();
        const monthDifference = today.getMonth() - inputDate.getMonth();
        const dayDifference = today.getDate() - inputDate.getDate();

        // Ajustar la edad si el mes actual es anterior al mes de nacimiento
        if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            age--;
        }

        // Validar si la fecha es hoy
        if (inputDate.toDateString() === today.toDateString()) {
            Swal.fire({
                title: 'Error',
                text: 'La fecha de nacimiento no puede ser hoy.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                fechaNacimientoInput.focus(); // Enfocar el campo de fecha
            });
            fechaNacimientoInput.setCustomValidity('La fecha de nacimiento no puede ser hoy.');
            return;
        }

        // Si la edad es menor a 18 años, mostrar un mensaje de advertencia
        if (age < 18) {
            Swal.fire({
                title: 'Error',
                text: 'Debes tener al menos 18 años para el registro.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                fechaNacimientoInput.focus(); // Enfocar el campo de fecha
            });
            fechaNacimientoInput.setCustomValidity('Debes tener al menos 18 años.');
        } else {
            fechaNacimientoInput.setCustomValidity('');
        }
    }

    // Agregar el evento para validar la fecha cuando se cambia el valor
    fechaNacimientoInput.addEventListener('input', validateDate);

    // Manejar el envío del formulario
    form.addEventListener('submit', function(event) {
        validateDate(); // Revalidar la fecha al momento del envío

        if (!form.checkValidity() || fechaNacimientoInput.validationMessage) {
            event.preventDefault(); // Evitar el envío del formulario si es inválido
            return;
        }
    });
});
