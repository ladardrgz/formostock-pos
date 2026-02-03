<?php
function validarDatosUsuario($conection, $data) {
    $errores = [];
    
    // Obtener los datos del formulario
    extract($data);

    if (empty($nombres) || strlen($nombres) > 50) {
        $errores[] = "El nombre no puede estar vacío y debe tener un máximo de 50 caracteres.";
    }

    if (empty($apellidos) || strlen($apellidos) > 50) {
        $errores[] = "El apellido no puede estar vacío y debe tener un máximo de 50 caracteres.";
    }

    // Validación de fecha de nacimiento
    $hoy = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires'));
    $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);

    if (!$fechaNacimiento) {
        $errores[] = "La fecha de nacimiento es requerida.";
    } else {
        // Verificar si la fecha seleccionada es hoy
        if ($fechaNacimiento->format('Y-m-d') === $hoy->format('Y-m-d')) {
            $errores[] = "La fecha no puede ser hoy.";
        }

        // Verificar que el usuario sea mayor de 18 años
        $fechaLimiteInferior = (clone $hoy)->modify('-18 years');
        if ($fechaNacimiento > $fechaLimiteInferior) {
            $errores[] = "Debes ser mayor de edad.";
        }

        // Verificar que el usuario no sea mayor de 100 años
        $fechaLimiteSuperior = (clone $hoy)->modify('-100 years');
        if ($fechaNacimiento < $fechaLimiteSuperior) {
            $errores[] = "La fecha de rango es inválida.";
        }
    }

    // Verificar otros campos
    if (empty($sexo)) {
        $errores[] = "Por favor, seleccione un género.";
    }

    if (empty($tipo_contacto_id)) {
        $errores[] = "Seleccione un tipo de contacto.";
    }

    if (empty($valor_detalle_contacto) || !preg_match('/^\d{10}$/', $valor_detalle_contacto)) {
        $errores[] = "Ingrese un número de teléfono válido (10 dígitos).";
    }

    if (empty($tipo_documento_id)) {
        $errores[] = "Seleccione un tipo de documento.";
    }

    if (empty($valor_documento)) {
        $errores[] = "El valor del documento no puede estar vacío.";
    }

    if (empty($pais_id)) {
        $errores[] = "Seleccione un país.";
    }

    if (empty($provincia_id)) {
        $errores[] = "Seleccione una provincia.";
    }

    if (empty($localidad_id)) {
        $errores[] = "Seleccione una localidad.";
    }

    if (empty($barrio_id)) {
        $errores[] = "Seleccione un barrio.";
    }

    if (empty($descripcion_domicilio) || strlen($descripcion_domicilio) > 150) {
        $errores[] = "La dirección no puede estar vacía y debe tener un máximo de 150 caracteres.";
    }

    // Validación para el nombre de cuenta de usuario
    if (empty($nombre_cuenta) || strlen($nombre_cuenta) < 4 || strlen($nombre_cuenta) > 32) {
        $errores[] = "El nombre de usuario debe tener entre 4 y 32 caracteres.";
    }

    // Validación del correo electrónico
    $dominios_validos = ["gmail.com", "hotmail.com", "yahoo.com", "outlook.com"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no tiene un formato válido.";
    } else {
        $dominio = explode('@', $email)[1];
        if (!in_array($dominio, $dominios_validos)) {
            $errores[] = "El correo electrónico no es de un dominio permitido.";
        }
    }

    // Validación de contraseñas
    if (empty($contraseñaUsuario) || strlen($contraseñaUsuario) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    if (empty($repetirContraseña)) {
        $errores[] = "Por favor, repita la contraseña.";
    }

    if ($contraseñaUsuario !== $repetirContraseña) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (empty($rol_id)) {
        $errores[] = "Seleccione un rol administrativo.";
    }

    return $errores;
}
?>
