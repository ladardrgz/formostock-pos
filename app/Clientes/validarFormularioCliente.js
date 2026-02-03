function limpiarMensajesErrores() {
    // Obtener todos los elementos con la clase 'error'
    const mensajesErrores = document.querySelectorAll('.error');

    // Eliminar cada mensaje de error
    mensajesErrores.forEach(mensaje => {
        mensaje.remove();
    });
}

function validarEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

function validarTelefono(telefono) {
    const telefonoPattern = /^\d{10}$/; // 10 dígitos
    return telefonoPattern.test(telefono);
}

function mostrarMensajeError(elemento, mensaje) {
    // Crear un nuevo elemento para mostrar el mensaje de error
    const errorElement = document.createElement('div');
    errorElement.className = 'error'; // Clase para poder limpiar más tarde
    errorElement.style.color = 'red'; // Estilo para que el texto sea rojo
    errorElement.innerText = mensaje;

    // Insertar el mensaje de error después del elemento
    elemento.parentNode.insertBefore(errorElement, elemento.nextSibling);
    elemento.focus(); // Focaliza el elemento con error
}

function obtenerFechaHoyArgentina() {
    const fechaActualUTC = new Date();
    const offsetArgentina = -3; // Offset de Argentina en relación al UTC
    const fechaHoyArgentina = new Date(fechaActualUTC.getTime() + offsetArgentina * 60 * 60 * 1000);
    fechaHoyArgentina.setHours(0, 0, 0, 0); // Establecer la hora a medianoche
    return fechaHoyArgentina;
}

function crearCliente(event) {
    event.preventDefault(); // Evitar el envío del formulario por defecto
    limpiarMensajesErrores(); // Asegúrate de que esta función esté definida

    // Obtener el formulario desde el botón que disparó el evento
    const form = event.target.closest('form'); // Encuentra el formulario más cercano

    // Obtener referencias a los elementos del formulario
    const nombres = document.getElementById('nombres');
    const apellidos = document.getElementById('apellidos');
    const fechaNacimiento = document.getElementById('fechaNacimiento');
    const sexo = document.getElementById('sexo');
    const tipo_documento_id = document.getElementById('tipoDocumento');
    const valorDocumento = document.getElementById('valorDocumento');
    const tipo_contacto_id = document.getElementById('tipoContacto');
    const valorDetalleContacto = document.getElementById('detalleContacto');
    const pais = document.getElementById('pais');
    const provincia = document.getElementById('provincia');
    const localidad = document.getElementById('localidad');
    const barrio = document.getElementById('barrio');
    const descripcionDomicilio = document.getElementById('descripcionDomicilio');

    let valido = true;

    // Validación de nombres
    if (nombres.value.trim() === '' || nombres.value.length > 50) {
        mostrarMensajeError(nombres, "El nombre no puede estar vacío y debe tener un máximo de 50 caracteres.");
        valido = false;
    }

    // Validación de apellidos
    if (apellidos.value.trim() === '' || apellidos.value.length > 50) {
        mostrarMensajeError(apellidos, "El apellido no puede estar vacío y debe tener un máximo de 50 caracteres.");
        valido = false;
    }

    // Validación de fecha de nacimiento
    const hoy = obtenerFechaHoyArgentina();
    const fechaLimiteInferior = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
    const fechaLimiteSuperior = new Date(hoy.getFullYear() - 100, hoy.getMonth(), hoy.getDate());

    if (!fechaNacimiento.value) {
        mostrarMensajeError(fechaNacimiento, "La fecha de nacimiento es requerida.");
        valido = false;
    } else {
        const fechaNacimientoValor = new Date(fechaNacimiento.value);
        fechaNacimientoValor.setHours(0, 0, 0, 0);

        if (fechaNacimientoValor.toDateString() === hoy.toDateString()) {
            mostrarMensajeError(fechaNacimiento, "La fecha no puede ser hoy.");
            valido = false;
        } else if (fechaNacimientoValor > fechaLimiteInferior) {
            mostrarMensajeError(fechaNacimiento, "Debes ser mayor de edad.");
            valido = false;
        } else if (fechaNacimientoValor < fechaLimiteSuperior) {
            mostrarMensajeError(fechaNacimiento, "La fecha de rango es inválida.");
            valido = false;
        }
    }

    if (!sexo.value) {
        mostrarMensajeError(sexo, "Por favor, seleccione un género.");
        valido = false;
    }

    if (tipo_contacto_id.value === '') {
        mostrarMensajeError(tipo_contacto_id, "Seleccione un tipo de contacto.");
        valido = false;
    }

    if (!valorDetalleContacto.value || !validarTelefono(valorDetalleContacto.value)) {
        mostrarMensajeError(valorDetalleContacto, "Ingrese un número de teléfono válido (10 dígitos).");
        valido = false;
    }

    if (tipo_documento_id.value === '') {
        mostrarMensajeError(tipo_documento_id, "Seleccione un tipo de documento.");
        valido = false;
    }

    if (!valorDocumento.value) {
        mostrarMensajeError(valorDocumento, "El valor del documento no puede estar vacío.");
        valido = false;
    }

    if (pais.value === '') {
        mostrarMensajeError(pais, "Seleccione un país.");
        valido = false;
    }

    if (provincia.value === '') {
        mostrarMensajeError(provincia, "Seleccione una provincia.");
        valido = false;
    }

    if (localidad.value === '') {
        mostrarMensajeError(localidad, "Seleccione una localidad.");
        valido = false;
    }

    if (barrio.value === '') {
        mostrarMensajeError(barrio, "Seleccione un barrio.");
        valido = false;
    }

    if (!descripcionDomicilio.value || descripcionDomicilio.value.length > 150) {
        mostrarMensajeError(descripcionDomicilio, "La dirección no puede estar vacía y debe tener un máximo de 150 caracteres.");
        valido = false;
    }

    // Envío del formulario si es válido
    if (valido) {
        document.getElementById('cliente-form').submit(); // Envía el formulario
    }
}
