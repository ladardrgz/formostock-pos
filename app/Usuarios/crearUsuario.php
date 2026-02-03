<?php
session_start();
include '../modelos/conexion.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/FormoStock/app/assets/css/styleCrearUsuario.css">
    <link rel="stylesheet" href="/FormoStock/app/assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="/FormoStock/app/assets/img/IconoLog.ico">
</head>

<body>
    <?php include 'nav_usuarios.php'; ?>
    <div class="container">
        <h1>Crear usuario</h1>
        <form action="recibirUser.php" id="formUsuario" method="POST">
            <!-- Datos de cuenta -->
            <div class="form-group">
                <label for="nombreCuentaUsuario" class="form-label">Nombre de usuario</label>
                <input type="text" id="nombreCuentaUsuario" placeholder="Nombre de usuario" name="nombreCuentaUsuario" minlength="8" maxlength="30" required class="form-control">
            </div>

            <div class="form-group">
                <label for="contraseñaUsuario">Contrase&ntilde;a</label>
                <div class="input-group">
                    <input type="password" name="contraseñaUsuario" id="contraseñaUsuario" placeholder="Contrase&ntilde;a" minlength="4" required maxlength="32" class="form-control">
                    <span class="input-group-text" id="eyeContraseña" onclick="cambiarVisibilidadContraseña('contraseñaUsuario', this)" style="cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="repetirContraseña">Repetir contrase&ntilde;a</label>
                <div class="input-group">
                    <input type="password" name="repetirContraseña" id="repetirContraseña" placeholder="Repetir contrase&ntilde;a" required minlength="4" maxlength="32" class="form-control">
                    <span class="input-group-text" id="eyeRepetirContraseña" onclick="cambiarVisibilidadContraseña('repetirContraseña', this)" style="cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="emailUsuario" class="form-label">Correo electr&oacute;nico</label>
                <input type="email" id="emailUsuario" placeholder="Correo electr&oacute;nico" name="emailUsuario" minlength="10" maxlength="150" required class="form-control">
            </div>

            <input type="hidden" id="estado_usuario_id" name="estado_usuario_id" value="1">

            <!-- Información personal -->
            <h3>Informaci&oacute;n personal</h3>

            <div class="form-group">
                <label for="nombres" class="form-label">Nombre</label>
                <input type="text" id="nombres" placeholder="Nombre" name="nombres" minlength="2" maxlength="50" required class="form-control">
            </div>

            <div class="form-group">
                <label for="apellidos" class="form-label">Apellido</label>
                <input type="text" id="apellidos" placeholder="Apellido" name="apellidos" minlength="2" maxlength="50" required class="form-control">
            </div>

            <div class="form-group">
                <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" id="fechaNacimiento" name="fechaNacimiento" required class="form-control" max="<?php echo date('Y-m-d'); ?>" min="1924-01-01">
            </div>

            <div class="form-group">
                <label for="sexo" class="form-label">G&eacute;nero</label>
                <select id="sexo" placeholder="G&eacute;nero" name="sexo" class="form-select" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="No binario">No binario</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_documento_id" class="form-label">Seleccione el tipo de documento</label>
                <select id="tipo_documento_id" name="tipo_documento_id" placeholder="Tipo de documento" required class="form-select">
                    <option value="">Seleccione el tipo de documento</option>
                    <?php
                    $query = "SELECT idTipoDocumento, nombreTipoDoc FROM tb_tipo_documentos";
                    $result = mysqli_query($conection, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['idTipoDocumento'] . "'>" . $row['nombreTipoDoc'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="valorDocumento" class="form-label">Ingrese el valor del documento</label>
                <input type="text" id="valorDocumento" placeholder="Valor del documento" name="valorDocumento" maxlength="100" required class="form-control">
            </div>

            <div class="form-group">
                <label for="tipo_contacto_id" class="form-label">Seleccione un tipo de contacto</label>
                <select id="tipo_contacto_id" placeholder="Tipo de contacto" name="tipo_contacto_id" required class="form-select">
                    <option value="">Seleccione el tipo de contacto</option>
                    <?php
                    $query_contacto = "SELECT idTipoContacto, nombreTipoContacto FROM tb_tipo_contacto";
                    $result_contacto = mysqli_query($conection, $query_contacto);
                    while ($row_contacto = mysqli_fetch_assoc($result_contacto)) {
                        echo "<option value='" . $row_contacto['idTipoContacto'] . "'>" . $row_contacto['nombreTipoContacto'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="valorDetalleContacto" class="form-label">Ingrese el valor del contacto</label>
                <input type="text" id="valorDetalleContacto" placeholder="Valor de contacto" name="valorDetalleContacto" maxlength="150" required class="form-control">
            </div>

            <!-- Dirección -->
            <h3>Direcci&oacute;n</h3>

            <div class="form-group">
                <label for="pais" class="form-label">Pa&iacute;s</label>
                <select id="pais" name="pais" class="form-select" required>
                    <option value="">Selecciona el pa&iacute;s</option>
                    <?php
                    $sql_paises = "SELECT idPais, nombrePais FROM tb_paises";
                    $result_paises = $conection->query($sql_paises);
                    if ($result_paises->num_rows > 0) {
                        while ($row = $result_paises->fetch_assoc()) {
                            echo "<option value='" . $row["idPais"] . "'>" . $row["nombrePais"] . "</option>";
                        }
                    }
                    ?>
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
                <input type="text" id="descripcionDomicilio" placeholder="Ej: Brandsen 140" name="descripcionDomicilio" required maxlength="150" class="form-control">
            </div>

            <input type="hidden" id="estado_persona_id" name="estado_persona_id" value="1">

            <div class="form-group">
                <label for="rol_id" class="form-label">Seleccione el rol administrativo</label>
                <select id="rol_id" name="rol_id" required class="form-select">
                    <option value="">Seleccione el rol</option>
                    <?php
                    $query_roles = "SELECT idRol, nombreRol FROM tb_roles";
                    $result_roles = mysqli_query($conection, $query_roles);
                    while ($row_roles = mysqli_fetch_assoc($result_roles)) {
                        echo "<option value='" . $row_roles['idRol'] . "'>" . $row_roles['nombreRol'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn custom-btn">Crear usuario</button>
            </div>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="../assets/js/cargarDatos.js"></script>
<script src="validarUsuarioAlert.js"></script>

</body>

</html>