<?php
include('../modelos/conexion.php'); 

// Consulta para obtener los tipos de contacto
$query_contactos = "SELECT * FROM tb_tipo_contacto";
$result_contactos = mysqli_query($conection, $query_contactos);

if (!$result_contactos) {
    die("Error en la consulta de tipos de contacto: " . mysqli_error($conection));
}

$tipos_contacto = array();
while ($row = mysqli_fetch_assoc($result_contactos)) {
    $tipos_contacto[] = $row;
}

// Consulta para obtener los tipos de documento
$query_documentos = "SELECT * FROM tb_tipo_documentos";
$result_documentos = mysqli_query($conection, $query_documentos);

if (!$result_documentos) {
    die("Error en la consulta de tipos de documentos: " . mysqli_error($conection));
}

$tipos_documento = array();
while ($row = mysqli_fetch_assoc($result_documentos)) {
    $tipos_documento[] = $row;
}

// Consulta para obtener los países
$sql_paises = "SELECT idPais, nombrePais FROM tb_paises";
$result_paises = mysqli_query($conection, $sql_paises);

if (!$result_paises) {
    die("Error en la consulta de países: " . mysqli_error($conection));
}

// Obtener los datos de los países
$paises = array();
while ($row = mysqli_fetch_assoc($result_paises)) {
    $paises[] = $row;
}

// Cerrar la conexión
mysqli_close($conection);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    
    <style>
        body {
            background: url('../assets/img/background-black.png') no-repeat center center fixed;
        }

        nav {
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <?php include('nav_clientes.php'); ?>
    <div class="container">
        <h1>Crear cliente</h1>
        <form id="cliente-form" action="procesarCreacionCliente.php" method="post">
            <div class="form-group">
                <label for="nombres" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombres" name="nombres" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="apellidos" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
            </div>

            <div class="form-group">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipoContacto" class="form-label">Seleccione el tipo de contacto</label>
                <select class="form-select" id="tipoContacto" name="tipo_contacto_id" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <?php foreach ($tipos_contacto as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo['idTipoContacto']); ?>">
                            <?php echo htmlspecialchars($tipo['nombreTipoContacto']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="detalleContacto" class="form-label">Ingrese el valor del tipo de contacto</label>
                <input type="text" class="form-control" id="detalleContacto" name="valorDetalleContacto" maxlength="150" required>
            </div>

            <div class="form-group">
                <label for="tipoDocumento" class="form-label">Seleccione el tipo de documento</label>
                <select class="form-select" id="tipoDocumento" name="tipo_documento_id" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <?php foreach ($tipos_documento as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo['idTipoDocumento']); ?>">
                            <?php echo htmlspecialchars($tipo['nombreTipoDoc']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="valorDocumento" class="form-label">Ingrese el valor del documento</label>
                <input type="text" id="valorDocumento" name="valorDocumento" class="form-control" placeholder="Ingrese el valor del documento" required>
            </div>

            <h3>Dirección</h3>
            <div class="form-group">
                <label for="pais" class="form-label">País</label>
                <select id="pais" name="pais" class="form-select" required>
                    <option value="">Selecciona el país</option>
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?php echo htmlspecialchars($pais['idPais']); ?>">
                            <?php echo htmlspecialchars($pais['nombrePais']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="provincia" class="form-label">Provincia</label>
                <select id="provincia" name="provincia" class="form-select" required>
                    <option value="">Selecciona la provincia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="localidad" class="form-label">Localidad</label>
                <select id="localidad" name="localidad" class="form-select" required>
                    <option value="">Selecciona la localidad</option>
                </select>
            </div>

            <div class="form-group">
                <label for="barrio" class="form-label">Barrio</label>
                <select id="barrio" name="barrio" class="form-select" required>
                    <option value="">Selecciona el barrio</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcionDomicilio" class="form-label">Calle y altura</label>
                <input type="text" id="descripcionDomicilio" name="descripcionDomicilio" required maxlength="150" class="form-control">
            </div>

            <input type="hidden" name="estado_persona_id" value="1">

            <div class="text-center">
                <button type="submit" class="btn custom-btn" onclick="crearCliente(event)">Registrar cliente</button>
            </div>
        </form>
    </div>

    <script src="validarFormularioCliente.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/validate-date.js" defer></script>
</body>

</html>

    <script>
        document.getElementById('pais').addEventListener('change', function() {
            fetchProvincias(this.value);
        });

        document.getElementById('provincia').addEventListener('change', function() {
            fetchLocalidades(this.value);
        });

        document.getElementById('localidad').addEventListener('change', function() {
            fetchBarrios(this.value);
        });

        function fetchProvincias(paisId) {
            fetch('get_provincias.php?pais_id=' + paisId)
                .then(response => response.json())
                .then(data => {
                    let provinciaSelect = document.getElementById('provincia');
                    provinciaSelect.innerHTML = '<option value="">Selecciona la provincia</option>';
                    data.forEach(provincia => {
                        provinciaSelect.innerHTML += `<option value="${provincia.idProvincia}">${provincia.nombreProvincia}</option>`;
                    });
                })
                .catch(error => console.error('Error al cargar las provincias:', error));
        }

        function fetchLocalidades(provinciaId) {
            fetch('get_localidades.php?provincia_id=' + provinciaId)
                .then(response => response.json())
                .then(data => {
                    let localidadSelect = document.getElementById('localidad');
                    localidadSelect.innerHTML = '<option value="">Selecciona la localidad</option>';
                    data.forEach(localidad => {
                        localidadSelect.innerHTML += `<option value="${localidad.idLocalidad}">${localidad.nombreLocalidad}</option>`;
                    });
                })
                .catch(error => console.error('Error al cargar las localidades:', error));
        }

        function fetchBarrios(localidadId) {
            fetch('get_barrios.php?localidad_id=' + localidadId)
                .then(response => response.json())
                .then(data => {
                    let barrioSelect = document.getElementById('barrio');
                    barrioSelect.innerHTML = '<option value="">Selecciona el barrio</option>';
                    data.forEach(barrio => {
                        barrioSelect.innerHTML += `<option value="${barrio.idBarrio}">${barrio.nombreBarrio}</option>`;
                    });
                })
                .catch(error => console.error('Error al cargar los barrios:', error));
        }
    </script>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            const form = event.target;
            const formData = new FormData(form);
            let valido = true;

            // Validaciones
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

            // Validación de nombres
            if (!nombres.value.trim() || nombres.value.length > 50) {
                Swal.fire('Error', 'El nombre no puede estar vacío y debe tener un máximo de 50 caracteres.', 'error');
                valido = false;
            }

            // Validación de apellidos
            if (!apellidos.value.trim() || apellidos.value.length > 50) {
                Swal.fire('Error', 'El apellido no puede estar vacío y debe tener un máximo de 50 caracteres.', 'error');
                valido = false;
            }

            // Validación de fecha de nacimiento
            const hoy = new Date();
            const fechaNacimientoValor = new Date(fechaNacimiento.value);

            if (!fechaNacimiento.value) {
                Swal.fire('Error', 'La fecha de nacimiento es requerida.', 'error');
                valido = false;
            } else if (fechaNacimientoValor >= hoy) {
                Swal.fire('Error', 'La fecha no puede ser hoy ni en el futuro.', 'error');
                valido = false;
            }

            // Validación de sexo
            if (!sexo.value) {
                Swal.fire('Error', 'Por favor, seleccione un género.', 'error');
                valido = false;
            }

            // Validación de tipo de contacto
            if (!tipo_contacto_id.value) {
                Swal.fire('Error', 'Seleccione un tipo de contacto.', 'error');
                valido = false;
            }

            // Validación de detalle de contacto
            if (!valorDetalleContacto.value.trim() || !/^\d{10}$/.test(valorDetalleContacto.value)) {
                Swal.fire('Error', 'Ingrese un número de teléfono válido (10 dígitos).', 'error');
                valido = false;
            }

            // Validación de tipo de documento
            if (!tipo_documento_id.value) {
                Swal.fire('Error', 'Seleccione un tipo de documento.', 'error');
                valido = false;
            }

            // Validación de valor del documento
            if (!valorDocumento.value.trim()) {
                Swal.fire('Error', 'El valor del documento no puede estar vacío.', 'error');
                valido = false;
            }

            // Validación de país
            if (!pais.value) {
                Swal.fire('Error', 'Seleccione un país.', 'error');
                valido = false;
            }

            // Validación de provincia
            if (!provincia.value) {
                Swal.fire('Error', 'Seleccione una provincia.', 'error');
                valido = false;
            }

            // Validación de localidad
            if (!localidad.value) {
                Swal.fire('Error', 'Seleccione una localidad.', 'error');
                valido = false;
            }

            // Validación de barrio
            if (!barrio.value) {
                Swal.fire('Error', 'Seleccione un barrio.', 'error');
                valido = false;
            }

            // Validación de descripción del domicilio
            if (!descripcionDomicilio.value.trim() || descripcionDomicilio.value.length > 150) {
                Swal.fire('Error', 'La dirección no puede estar vacía y debe tener un máximo de 150 caracteres.', 'error');
                valido = false;
            }

            // Si todas las validaciones son correctas, enviar el formulario
            if (valido) {
                fetch('procesarCreacionCliente.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.status === 'success' ? 'Éxito' : 'Error',
                        text: data.message,
                        icon: data.status,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        if (data.status === 'success') {
                            form.reset(); 
                        }
                    });
                })
                .catch(() => {
                    Swal.fire('Error', 'Error de comunicación con el servidor', 'error');
                });
            }
        });
    });
</script>


</body>
</html>
