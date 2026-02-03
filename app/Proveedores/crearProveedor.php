<?php
session_start();
include '../modelos/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de proveedor</title>
    <!-- Bootstrap CSS: Estilos de diseño responsivo y componentes predefinidos. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <!-- Hoja de estilos personalizada: Estilos específicos para el menú de navegación principal. -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- Icono de la pestaña del navegador: Icono que se muestra en la pestaña del navegador. -->
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background: url('../assets/img/background-black.png') no-repeat center center fixed;
        }

        nav {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .btn {
            width: 100%;
        }

        .custom-btn {
            width: 30%;
            background-color: #7b5095;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .custom-btn:hover {
            background-color: #693e77;
            color: #fff;
        }

        .text-center {
            text-align: center;
            margin-bottom: 15px;
        }

        .fa-eye,
        .fa-eye-slash {
            font-size: 16px;
            line-height: 1.5;
        }

        .errorValidacion {
            color: red;
            font-size: 15px;
            margin-top: 15px;
            display: block;
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .input-group {
            align-items: center;
            /* Alinea verticalmente los elementos dentro del input-group */
        }

        .input-group-text {
            display: flex;
            align-items: center;
            /* Alinea verticalmente el icono dentro del span */
            padding: 0.375rem 0.75rem;
            /* Ajusta el padding según sea necesario */
            height: auto;
            /* Asegúrate de que el span no tenga una altura fija */
        }

        .form-control {
            padding-right: 38px;
            /* Para evitar que el icono de ojo se superponga al texto */
        }
    </style>
</head>

<body>
    <?php
    include 'nav_proveedores.php';
    ?>
    <div class="container">
        <h1>Crear proveedor</h1>

        <form action="pcr_registro_proveedor.php" method="post">
            <!-- Información Personal -->
            <h3>Información personal</h3>
            <div class="form-group">
                <label for="nombres">Nombre</label>
                <input type="text" name="nombres" id="nombres" class="form-control" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="apellidos">Apellido</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="fechaNacimiento">Fecha de nacimiento</label>
                <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="sexo">Género</label>
                <select name="sexo" id="sexo" class="form-control" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="No binario">No binario</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_documento_id">Tipo de documento</label>
                <select name="tipo_documento_id" id="tipo_documento_id" class="form-control" required>
                    <option value="">Seleccione un tipo de documento</option>
                    <?php
                    $tipos_documento = mysqli_query($conection, "SELECT * FROM tb_tipo_documentos");
                    while ($tipo_doc = mysqli_fetch_assoc($tipos_documento)) {
                        echo "<option value='{$tipo_doc['idTipoDocumento']}'>{$tipo_doc['nombreTipoDoc']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="valor_documento">Ingrese el valor</label>
                <input type="text" name="valor_documento" id="valor_documento" class="form-control" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="tipo_contacto_id">Tipo de contacto</label>
                <select name="tipo_contacto_id" id="tipo_contacto_id" class="form-control" required>
                    <option value="">Seleccione un tipo de contacto</option>
                    <?php
                    $tipos_contacto = mysqli_query($conection, "SELECT * FROM tb_tipo_contacto");
                    while ($tipo_contacto = mysqli_fetch_assoc($tipos_contacto)) {
                        echo "<option value='{$tipo_contacto['idTipoContacto']}'>{$tipo_contacto['nombreTipoContacto']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="valor_detalle_contacto">Ingrese el valor</label>
                <input type="text" name="valor_detalle_contacto" id="valor_detalle_contacto" class="form-control" required maxlength="150">
            </div>

            <h3>Información de proveedor</h3>
            <div class="form-group">
                <label for="razonSocial">Razón social</label>
                <input type="text" name="razonSocial" id="razonSocial" class="form-control" required maxlength="100">
            </div>

            <h3>Dirección</h3>
            <div class="form-group">
                <label for="pais">País</label>
                <select id="pais" name="pais_id" class="form-control" required>
                    <option value="">Seleccione un país</option>
                    <?php
                    $paises = mysqli_query($conection, "SELECT * FROM tb_paises");
                    while ($pais = mysqli_fetch_assoc($paises)) {
                        echo "<option value='{$pais['idPais']}'>{$pais['nombrePais']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select id="provincia" name="provincia_id" class="form-control" required>
                    <option value="">Seleccione una provincia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad</label>
                <select id="localidad" name="localidad_id" class="form-control" required>
                    <option value="">Seleccione una localidad</option>
                </select>
            </div>

            <div class="form-group">
                <label for="barrio">Barrio</label>
                <select id="barrio" name="barrio_id" class="form-control" required>
                    <option value="">Seleccione un barrio</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcionDomicilio">Calle y altura</label>
                <input type="text" name="descripcionDomicilio" id="descripcionDomicilio" class="form-control" required maxlength="150">
            </div>

            <input type="hidden" name="estado_persona_juridica_id" value="1">

            <!-- Botón de Envío -->
            <div class="text-center">
                <input type="submit" value="Registrar proveedor" class="btn custom-btn" onclick="crearProveedor(event)">
            </div>
        </form>
    </div>


    <!-- Scripts -->
    <script>
        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert_type']; ?>',
                title: '<?php echo $_SESSION['message']; ?>',
            });
            <?php unset($_SESSION['message']);
            unset($_SESSION['alert_type']); ?>
        <?php endif; ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="obtener-direccion.js"></script>

    <script>
        // Función para limpiar todos los mensajes de error
        function limpiarMensajesErrores() {
            // Obtener todos los elementos con la clase 'error'
            const mensajesErrores = document.querySelectorAll('.error');

            // Eliminar cada mensaje de error
            mensajesErrores.forEach(mensaje => {
                mensaje.remove();
            });
        }

        // Validación de email (puedes agregarlo si tienes un campo de email en tu formulario)
        function validarEmail(email) {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        // Validación de número de teléfono
        function validarTelefono(telefono) {
            const telefonoPattern = /^\d{10}$/; // 10 dígitos
            return telefonoPattern.test(telefono);
        }

        // Función para mostrar mensajes de error
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

        // Obtener la fecha actual en el huso horario de Argentina
        function obtenerFechaHoyArgentina() {
            const fechaActualUTC = new Date();
            const offsetArgentina = -3; // Offset de Argentina en relación al UTC
            const fechaHoyArgentina = new Date(fechaActualUTC.getTime() + offsetArgentina * 60 * 60 * 1000);
            fechaHoyArgentina.setHours(0, 0, 0, 0); // Establecer la hora a medianoche
            return fechaHoyArgentina;
        }

        // Función para validar el formulario antes de su envío
        function crearProveedor(event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto
            limpiarMensajesErrores(); // Limpiar mensajes de error previos

            // Obtener referencias a los elementos del formulario
            const nombres = document.getElementById('nombres');
            const apellidos = document.getElementById('apellidos');
            const fechaNacimiento = document.getElementById('fechaNacimiento');
            const sexo = document.getElementById('sexo');
            const tipo_documento_id = document.getElementById('tipo_documento_id');
            const valorDocumento = document.getElementById('valor_documento');
            const tipo_contacto_id = document.getElementById('tipo_contacto_id');
            const valorDetalleContacto = document.getElementById('valor_detalle_contacto');
            const pais = document.getElementById('pais');
            const provincia = document.getElementById('provincia');
            const localidad = document.getElementById('localidad');
            const barrio = document.getElementById('barrio');
            const descripcionDomicilio = document.getElementById('descripcionDomicilio');

            let valido = true; // Bandera para verificar si todo es válido

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
                    mostrarMensajeError(fechaNacimiento, "La fecha de nacimiento es inválida.");
                    valido = false;
                }
            }

            // Validación de género
            if (!sexo.value) {
                mostrarMensajeError(sexo, "Por favor, seleccione un género.");
                valido = false;
            }

            // Validación del tipo de contacto
            if (tipo_contacto_id.value === '') {
                mostrarMensajeError(tipo_contacto_id, "Seleccione un tipo de contacto.");
                valido = false;
            }

            // Validación del número de contacto
            if (!valorDetalleContacto.value || !validarTelefono(valorDetalleContacto.value)) {
                mostrarMensajeError(valorDetalleContacto, "Ingrese un número de teléfono válido (10 dígitos).");
                valido = false;
            }

            // Validación del tipo de documento
            if (tipo_documento_id.value === '') {
                mostrarMensajeError(tipo_documento_id, "Seleccione un tipo de documento.");
                valido = false;
            }

            // Validación del valor del documento
            if (!valorDocumento.value) {
                mostrarMensajeError(valorDocumento, "El valor del documento no puede estar vacío.");
                valido = false;
            }

            // Validación de país
            if (pais.value === '') {
                mostrarMensajeError(pais, "Seleccione un país.");
                valido = false;
            }

            // Validación de provincia
            if (provincia.value === '') {
                mostrarMensajeError(provincia, "Seleccione una provincia.");
                valido = false;
            }

            // Validación de localidad
            if (localidad.value === '') {
                mostrarMensajeError(localidad, "Seleccione una localidad.");
                valido = false;
            }

            // Validación de barrio
            if (barrio.value === '') {
                mostrarMensajeError(barrio, "Seleccione un barrio.");
                valido = false;
            }

            // Validación de la dirección
            if (!descripcionDomicilio.value || descripcionDomicilio.value.length > 150) {
                mostrarMensajeError(descripcionDomicilio, "La dirección no puede estar vacía y debe tener un máximo de 150 caracteres.");
                valido = false;
            }

            // Enviar el formulario si es válido
            if (valido) {
                document.getElementById('registroProveedorForm').submit(); // Envía el formulario
            }
        }

        // Asignar la función de validación al evento de envío del formulario
        document.querySelector('form').addEventListener('submit', crearProveedor);
    </script>

</body>

</html>