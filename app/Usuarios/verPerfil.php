<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Incluir el archivo de conexión
require '../modelos/conexion.php'; // Asegúrate de que la ruta sea correcta

// Obtén el ID del usuario logueado desde la sesión
$idUsuario = $_SESSION['idUsuario']; // Asegúrate de que este ID esté almacenado en la sesión

// Prepara y ejecuta la consulta
$query = "
    SELECT 
        u.idUsuario, 
        u.nombreCuentaUsuario, 
        u.emailUsuario, 
        pf.nombres, 
        pf.apellidos, 
        pf.fechaNacimiento, 
        pf.sexo,
        dd.valorDocumento
    FROM 
        tb_usuarios u
    INNER JOIN 
        tb_personas_fisicas pf ON u.persona_fisica_id = pf.idPersonaFisica
    INNER JOIN 
        tb_detalle_documento dd ON pf.detalle_documento_id = dd.idDetalleDocumento
    WHERE 
        u.idUsuario = '$idUsuario'";

$result = mysqli_query($conection, $query);

// Verifica si el usuario existe
if ($result && mysqli_num_rows($result) > 0) {
    $usuario = mysqli_fetch_assoc($result);
} else {
    echo "Usuario no encontrado.";
    exit();
}

mysqli_close($conection); // Cierra la conexión a la base de datos

// Verifica si hay un mensaje en la sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo = $_SESSION['alert_type']; // "Éxito" o "Error"
    unset($_SESSION['mensaje']); // Limpia el mensaje después de mostrarlo
    unset($_SESSION['alert_type']); // Limpia el tipo de alerta después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar tu cuenta de FormoStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            background-image: url(../assets/img/background-black.png);
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
            /* Aumenta el margen superior para dar espacio entre el nav y el formulario */
            width: 100%;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .link {
            color: #007BFF;
            cursor: pointer;
            text-decoration: underline;
        }

        .link:hover {
            color: #0056b3;
        }

        .icon {
            margin-right: 10px;
            color: #9D27B0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        button {
            background-color: #7B5095;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 35%;
            display: block;
            /* Asegura que el botón sea un bloque */
            margin: 0 auto;
            /* Centra el botón */
        }

        button:hover {
            background-color: #503459;
        }

        .password-management {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .password-management img {
            width: 54px;
            /* Ajusta el tamaño de la imagen */
            height: 54px;
            margin-right: 8px;
            /* Espacio entre la imagen y el texto */
        }
    </style>
</head>

<body>
    <?php include 'nav_usuarios.php'; ?>
    <div class="container">
        <h2>Gestionar tu cuenta de FormoStock</h2>

        <?php if (isset($mensaje)): ?>
            <script>
                Swal.fire({
                    title: '<?php echo $tipo; ?>',
                    text: '<?php echo $mensaje; ?>',
                    icon: '<?php echo $tipo; ?>',
                    confirmButtonText: 'Aceptar'
                });
            </script>
        <?php endif; ?>

        <form method="POST" action="actualizar_usuario_perfil.php"> <!-- Asegúrate de que la acción apunte a tu archivo PHP para manejar la actualización -->

            <div class="section">
                <h3><i class="fas fa-user icon"></i>Información básica</h3>
                <div class="form-group">
                    <label class="label" for="nombre">Nombre y apellido</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="label" for="fecha-nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha-nacimiento" name="fecha-nacimiento" value="<?php echo htmlspecialchars($usuario['fechaNacimiento']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="label" for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="masculino" <?php if ($usuario['sexo'] == 'masculino') echo 'selected'; ?>>Masculino</option>
                        <option value="femenino" <?php if ($usuario['sexo'] == 'femenino') echo 'selected'; ?>>Femenino</option>
                        <option value="otro" <?php if ($usuario['sexo'] == 'otro') echo 'selected'; ?>>No binario</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-envelope icon"></i>Información de contacto</h3>
                <div class="form-group">
                    <label class="label" for="correo">Correo electrónico</label>
                    <input type="text" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['emailUsuario']); ?>" required>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-user icon"></i>Información de usuario</h3>
                <div class="form-group">
                    <label class="label" for="nombre-usuario">Nombre de cuenta de usuario</label>
                    <input type="text" id="nombre-usuario" name="nombre-usuario" value="<?php echo htmlspecialchars($usuario['nombreCuentaUsuario']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="label" for="valor-documento">Documento</label>
                    <input type="text" id="valor-documento" name="valor-documento" value="<?php echo htmlspecialchars($usuario['valorDocumento']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="label" for="contrasena">Contraseña</label>
                    <input type="text" id="contrasena" name="contrasena" value="***********" disabled>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-lock icon"></i>Contraseña</h3>
                <div class="password-management">
                    <img src="../assets/img/actualizacion-de-contrasena.png" alt="Icono de candado">
                    <p>Gestionar tu contraseña <span class="link" id="gestionarContrasena">aquí</span></p>
                </div>
            </div>

            <button type="button" id="btnActualizar">Actualizar información</button>
            </div>
        </form>
    </div>

    <script>
    // Almacenar los valores originales de los campos al cargar la página
    const originalValues = {
        nombre: document.getElementById('nombre').value,
        fechaNacimiento: document.getElementById('fecha-nacimiento').value,
        sexo: document.getElementById('sexo').value,
        correo: document.getElementById('correo').value,
        nombreUsuario: document.getElementById('nombre-usuario').value,
        valorDocumento: document.getElementById('valor-documento').value
    };

    // Función para mostrar mensaje de error
    function mostrarMensajeError(input, mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje
        });
    }

    // Función para obtener la fecha de hoy en la zona horaria de Argentina
    function obtenerFechaHoyArgentina() {
        const fechaActualUTC = new Date();
        const offsetArgentina = -3; // Offset de Argentina en relación al UTC
        const fechaHoyArgentina = new Date(fechaActualUTC.getTime() + offsetArgentina * 60 * 60 * 1000);
        // Establecer la hora a medianoche
        fechaHoyArgentina.setHours(0, 0, 0, 0);
        return fechaHoyArgentina;
    }

    // Función para validar fecha de nacimiento
    function validarFechaNacimiento() {
        const hoy = obtenerFechaHoyArgentina(); // Usar la función para obtener la fecha de hoy
        const fechaLimiteInferior = new Date(hoy.getFullYear() - 18, hoy.getMonth(), hoy.getDate());
        const fechaLimiteSuperior = new Date(hoy.getFullYear() - 100, hoy.getMonth(), hoy.getDate());
        const fechaNacimiento = document.getElementById('fecha-nacimiento');

        let valido = true;

        if (!fechaNacimiento.value) {
            mostrarMensajeError(fechaNacimiento, "La fecha de nacimiento es requerida.");
            valido = false;
        } else {
            const fechaNacimientoValor = new Date(fechaNacimiento.value);
            // Establecer la hora de la fecha de nacimiento a medianoche
            fechaNacimientoValor.setHours(0, 0, 0, 0);

            // Verificar si la fecha ingresada es válida
            if (isNaN(fechaNacimientoValor.getTime())) {
                mostrarMensajeError(fechaNacimiento, "La fecha ingresada no es válida.");
                valido = false;
            }
            // Verificar si la fecha seleccionada es hoy
            else if (fechaNacimientoValor.toDateString() === hoy.toDateString()) {
                mostrarMensajeError(fechaNacimiento, "La fecha no puede ser hoy.");
                valido = false;
            }
            // Verificar que el usuario sea mayor de 18 años
            else if (fechaNacimientoValor > fechaLimiteInferior) {
                mostrarMensajeError(fechaNacimiento, "Debes ser mayor de edad.");
                valido = false;
            }
            // Verificar que el usuario no sea mayor de 100 años
            else if (fechaNacimientoValor < fechaLimiteSuperior) {
                mostrarMensajeError(fechaNacimiento, "La fecha es inválida");
                valido = false;
            }
        }

        return valido;
    }

    // Función para actualizar información
    async function actualizarInformacion() {
        limpiarMensajesErrores();

        // Validar fecha de nacimiento
        if (!validarFechaNacimiento()) {
            return; // Si la validación falla, salir de la función
        }

        // Comprobar si los valores han cambiado
        if (
            originalValues.nombre === document.getElementById('nombre').value &&
            originalValues.fechaNacimiento === document.getElementById('fecha-nacimiento').value &&
            originalValues.sexo === document.getElementById('sexo').value &&
            originalValues.correo === document.getElementById('correo').value &&
            originalValues.nombreUsuario === document.getElementById('nombre-usuario').value &&
            originalValues.valorDocumento === document.getElementById('valor-documento').value
        ) {
            Swal.fire({
                title: 'Sin cambios',
                text: 'No se han realizado cambios en la información. Por favor, modifica algún campo antes de guardar.',
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
            return; // Salir de la función
        }

        // Aquí puedes agregar la lógica para enviar los datos a tu servidor o hacer lo que necesites
        Swal.fire({
            title: 'Información actualizada',
            text: 'La información se ha actualizado correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });

        // Luego puedes restablecer los valores originales después de una actualización exitosa
        Object.keys(originalValues).forEach(key => {
            originalValues[key] = document.getElementById(key).value;
        });
    }

    // Función para limpiar mensajes de error (si necesitas)
    function limpiarMensajesErrores() {
        // Aquí puedes agregar la lógica para limpiar los mensajes de error en la interfaz si es necesario
    }

    // Manejar el clic en el botón de actualización
    document.getElementById('btnActualizar').onclick = actualizarInformacion;

    document.getElementById('gestionarContrasena').onclick = function() {
        Swal.fire({
            title: '¿Deseas gestionar tu contraseña?',
            text: "Serás redirigido a la gestión de tu contraseña.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6181e7',
            cancelButtonColor: '#cd4646',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige a la página de gestión de contraseña
                window.location.href = 'gestionarContraseña.php'; // Cambia esta ruta a la página correspondiente
            }
        });
    };

    <?php if (isset($mensaje)): ?>
        Swal.fire({
            title: "<?php echo $tipo; ?>", // "Éxito" o "Error"
            text: "<?php echo $mensaje; ?>",
            icon: "<?php echo $tipo === 'Éxito' ? 'success' : 'error'; ?>", // Cambia el icono según el tipo
            confirmButtonText: 'Aceptar'
        });
    <?php endif; ?>
    </script>
</body>
</html>