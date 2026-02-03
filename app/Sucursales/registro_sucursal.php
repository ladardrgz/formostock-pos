<?php
include_once 'Database.php';
include_once 'TipoDocumento.php';
include_once 'TipoContacto.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancias de TipoDocumento y TipoContacto y obtener datos
$tipoDocumento = new TipoDocumento($db);
$tipoContacto = new TipoContacto($db);

$stmtTipoDocumento = $tipoDocumento->obtenerTiposDocumento();
$stmtTipoContacto = $tipoContacto->obtenerTiposContacto();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de sucursal</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            box-sizing: border-box;
            background-image: url('../assets/img/background-black.png');
        }

        #container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
            padding-top: 80px;
        }

        .caja {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            color: #333;
        }

        h2, h3 {
            color: #333333;
            text-align: center;
        }

        input[type="submit"] {
            background-color: #9b59b6;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #8e44ad;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        validate.extend(validate.validators.datetime, {
            parse: function(value) {
                return +moment.utc(value);
            },
            format: function(value, options) {
                var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                return moment.utc(value).format(format);
            }
        });

        function validateForm() {
            var constraints = {
                valorDocumento: {
                    presence: true,
                    length: {
                        minimum: 5,
                        message: "Debe tener al menos 5 caracteres"
                    }
                },
                fechaNacimiento: {
                    datetime: {
                        dateOnly: true,
                        latest: moment.utc().subtract(18, 'years'),
                        message: "Debes tener al menos 18 años"
                    }
                }
            };

            var form = document.querySelector('form');
            var formValues = {
                valorDocumento: form.querySelector('#valorDocumento').value,
                fechaNacimiento: form.querySelector('#fechaNacimiento').value
            };

            var errors = validate(formValues, constraints);

            if (errors) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: Object.values(errors).flat().join('\n'),
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php include 'MenuNavegacionSucursales.php'; ?>
    <div id="container">
        <div class="caja">
            <h2>Registro de sucursal</h2>
            <form id="formSucursal" action="guardar_sucursal.php" method="POST" onsubmit="return validateForm()">
                <h3>Representante legal</h3>
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                </div>

                <div class="mb-3">
                    <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                </div>

                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select id="sexo" name="sexo" class="form-select" required>
                        <option value="" disabled selected>Seleccionar</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo_documento_id" class="form-label">Tipo de documento</label>
                    <select id="tipo_documento_id" name="tipo_documento_id" class="form-select" required>
                        <?php
                        while ($row = $stmtTipoDocumento->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['idTipoDocumento'] . "'>" . $row['nombreTipoDoc'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="valorDocumento" class="form-label">Número de documento</label>
                    <input type="text" class="form-control" id="valorDocumento" name="valorDocumento" required>
                </div>

                <div class="mb-3">
                    <label for="tipo_contacto_id" class="form-label">Seleccione un tipo de contacto</label>
                    <select id="tipo_contacto_id" name="tipo_contacto_id" class="form-select" required>
                        <?php
                        while ($row = $stmtTipoContacto->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['idTipoContacto'] . "'>" . $row['nombreTipoContacto'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="valorContacto" class="form-label">Ingrese el valor del tipo de contacto</label>
                    <input type="text" class="form-control" id="valorContacto" name="valorContacto" required>
                </div>

                <h3>Entidad legal</h3>
                <div class="mb-3">
                    <label for="razonSocial" class="form-label">Razón social</label>
                    <input type="text" class="form-control" id="razonSocial" name="razonSocial" required>
                </div>

                <h3>Información de sucursal</h3>
                <div class="mb-3">
                    <label for="nombreSucursal" class="form-label">Nombre de la sucursal</label>
                    <input type="text" class="form-control" id="nombreSucursal" name="nombreSucursal" required>
                </div>

                <div class="text-center">
                    <input type="submit" class="btn btn-primary w-100" onclick="crearSucursal(event)" value="Registrar">
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    function crearSucursal(event) {
        // Evitar el envío del formulario hasta que se realicen las validaciones
        event.preventDefault();

        // Limpiar mensajes de error anteriores
        clearErrors();

        // Llamar a la función de validación
        if (validateForm()) {
            // Si la validación es exitosa, puedes hacer algo aquí, como enviar el formulario
            document.getElementById('formSucursal').submit();
        }
    }

    function validateForm() {
        // Obtener todos los campos del formulario
        const nombres = document.getElementById('nombres').value.trim();
        const apellidos = document.getElementById('apellidos').value.trim();
        const fechaNacimiento = document.getElementById('fechaNacimiento').value;
        const sexo = document.getElementById('sexo').value;
        const tipoDocumento = document.getElementById('tipo_documento_id').value;
        const valorDocumento = document.getElementById('valorDocumento').value.trim();
        const tipoContacto = document.getElementById('tipo_contacto_id').value;
        const valorContacto = document.getElementById('valorContacto').value.trim();
        const razonSocial = document.getElementById('razonSocial').value.trim();
        const nombreSucursal = document.getElementById('nombreSucursal').value.trim();
        
        let isValid = true; // Inicializa la variable de validez

        // Verificar si los campos están vacíos y mostrar mensajes de error
        if (!nombres) {
            showError('nombres', "Por favor, complete el nombre.");
            isValid = false;
        }
        if (!apellidos) {
            showError('apellidos', "Por favor, complete el apellido.");
            isValid = false;
        }
        if (!fechaNacimiento) {
            showError('fechaNacimiento', "Por favor, seleccione la fecha de nacimiento.");
            isValid = false;
        }
        if (!sexo) {
            showError('sexo', "Por favor, seleccione el sexo.");
            isValid = false;
        }
        if (!tipoDocumento) {
            showError('tipo_documento_id', "Por favor, seleccione un tipo de documento.");
            isValid = false;
        }
        if (!valorDocumento) {
            showError('valorDocumento', "Por favor, complete el número de documento.");
            isValid = false;
        } else if (!/^\d+$/.test(valorDocumento)) {
            showError('valorDocumento', "El número de documento debe contener solo números.");
            isValid = false;
        }
        if (!tipoContacto) {
            showError('tipo_contacto_id', "Por favor, seleccione un tipo de contacto.");
            isValid = false;
        }
        if (!valorContacto) {
            showError('valorContacto', "Por favor, ingrese el valor del tipo de contacto.");
            isValid = false;
        } else if (!/^[\w\s.-]+$/.test(valorContacto)) {
            showError('valorContacto', "El valor del tipo de contacto solo puede contener letras, números y los siguientes caracteres: espacio, punto, guion y guion bajo.");
            isValid = false;
        }
        if (!razonSocial) {
            showError('razonSocial', "Por favor, complete la razón social.");
            isValid = false;
        }
        if (!nombreSucursal) {
            showError('nombreSucursal', "Por favor, complete el nombre de la sucursal.");
            isValid = false;
        }

        return isValid; // Devuelve el estado de validez
    }

    function showError(fieldId, message) {
        // Crear un elemento de mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = 'red'; // Cambiar el color a rojo
        errorDiv.innerText = message;

        // Agregar el mensaje de error debajo del campo correspondiente
        const field = document.getElementById(fieldId);
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }

    function clearErrors() {
        // Eliminar todos los mensajes de error anteriores
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach((msg) => msg.remove());
    }
</script>


    <script>
        $(document).ready(function() {
            $('#formSucursal').on('submit', function(event) {
                event.preventDefault(); // Evita el envío normal del formulario
                
                $.ajax({
                    type: "POST",
                    url: "guardar_sucursal.php",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload(); // Recargar la página
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado. Intente nuevamente.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
