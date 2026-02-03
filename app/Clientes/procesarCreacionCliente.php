<?php
include('../modelos/conexion.php'); 

$response = array();

// Verificar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombres = isset($_POST['nombres']) ? $_POST['nombres'] : '';
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
    $fechaNacimiento = isset($_POST['fechaNacimiento']) ? $_POST['fechaNacimiento'] : '';
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $tipo_contacto_id = isset($_POST['tipo_contacto_id']) ? (int) $_POST['tipo_contacto_id'] : 0;
    $valorDetalleContacto = isset($_POST['valorDetalleContacto']) ? $_POST['valorDetalleContacto'] : '';
    $tipo_documento_id = isset($_POST['tipo_documento_id']) ? (int) $_POST['tipo_documento_id'] : 0;
    $valorDocumento = isset($_POST['valorDocumento']) ? $_POST['valorDocumento'] : '';
    $estado_persona_id = isset($_POST['estado_persona_id']) ? (int) $_POST['estado_persona_id'] : 0;

    // Obtener los datos de la dirección
    $barrio_id = isset($_POST['barrio']) ? (int) $_POST['barrio'] : 0;
    $descripcionDomicilio = isset($_POST['descripcionDomicilio']) ? $_POST['descripcionDomicilio'] : '';

    // Verificar si el estado lógico existe
    $query_estado = "SELECT * FROM tb_estados_logicos WHERE idEstLog = $estado_persona_id";
    $result_estado = mysqli_query($conection, $query_estado);

    if (mysqli_num_rows($result_estado) == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Estado lógico no válido';
        header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
        mysqli_close($conection);
        exit();
    }

    // Insertar en la tabla tb_detalle_contacto
    $query_detalle_contacto = "
        INSERT INTO tb_detalle_contacto (tipo_contacto_id, valorDetalleContacto)
        VALUES ($tipo_contacto_id, '$valorDetalleContacto')
    ";
    if (mysqli_query($conection, $query_detalle_contacto)) {
        $idDetalleContacto = mysqli_insert_id($conection);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al insertar detalle de contacto: ' . mysqli_error($conection);
        header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
        mysqli_close($conection);
        exit();
    }

    // Insertar en la tabla tb_detalle_documento
    $query_detalle_documento = "
        INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento)
        VALUES ($tipo_documento_id, '$valorDocumento')
    ";
    if (mysqli_query($conection, $query_detalle_documento)) {
        $idDetalleDocumento = mysqli_insert_id($conection);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al insertar detalle de documento: ' . mysqli_error($conection);
        header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
        mysqli_close($conection);
        exit();
    }

    // Insertar en la tabla tb_personas_fisicas
    $query_persona = "
        INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, detalle_documento_id, detalle_contacto_id, estado_persona_id)
        VALUES ('$nombres', '$apellidos', '$fechaNacimiento', '$sexo', $idDetalleDocumento, $idDetalleContacto, $estado_persona_id)
    ";
    if (mysqli_query($conection, $query_persona)) {
        $idPersonaFisica = mysqli_insert_id($conection);

        // Insertar en la tabla tb_domicilios
        $query_domicilio = "
            INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id)
            VALUES ('$descripcionDomicilio', $barrio_id)
        ";
        if (mysqli_query($conection, $query_domicilio)) {
            $idDomicilio = mysqli_insert_id($conection);

            // Insertar en la tabla tb_domicilios_personas
            $query_domicilio_persona = "
                INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id)
                VALUES ('$descripcionDomicilio', $idPersonaFisica, $idDomicilio)
            ";
            if (mysqli_query($conection, $query_domicilio_persona)) {
                // Insertar en la tabla tb_clientes
                $query_cliente = "
                    INSERT INTO tb_clientes (persona_fisica_id)
                    VALUES ($idPersonaFisica)
                ";
                if (mysqli_query($conection, $query_cliente)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Cliente y domicilio registrados con éxito';
                    header('Location: crearCliente.php?status=success&message=' . urlencode($response['message']));
                    mysqli_close($conection);
                    exit();
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al insertar en tb_clientes: ' . mysqli_error($conection);
                    header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
                    mysqli_close($conection);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al insertar en tb_domicilios_personas: ' . mysqli_error($conection);
                header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
                mysqli_close($conection);
                exit();
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al insertar domicilio: ' . mysqli_error($conection);
            header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
            mysqli_close($conection);
            exit();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al registrar persona física: ' . mysqli_error($conection);
        header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
        mysqli_close($conection);
        exit();
    }

} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no permitido';
    header('Location: crearCliente.php?status=error&message=' . urlencode($response['message']));
    exit();
}
?>
