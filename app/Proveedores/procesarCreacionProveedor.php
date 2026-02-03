<?php
session_start(); // Iniciar sesión
include '../modelos/conexion.php'; // Incluir el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombres = mysqli_real_escape_string($conection, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conection, $_POST['apellidos']);
    $fechaNacimiento = mysqli_real_escape_string($conection, $_POST['fechaNacimiento']);
    $sexo = mysqli_real_escape_string($conection, $_POST['sexo']);
    $razonSocial = mysqli_real_escape_string($conection, $_POST['razonSocial']);
    $descripcionDomicilio = mysqli_real_escape_string($conection, $_POST['descripcionDomicilio']);
    
    // Datos del documento
    $tipoDocumentoId = (int) mysqli_real_escape_string($conection, $_POST['tipo_documento_id']); // ID del tipo de documento
    $valorDocumento = mysqli_real_escape_string($conection, $_POST['valor_documento']); // Valor del documento

    // Datos de contacto
    $tipoContactoId = (int) mysqli_real_escape_string($conection, $_POST['tipo_contacto_id']); // ID del tipo de contacto
    $valorContacto = mysqli_real_escape_string($conection, $_POST['valor_detalle_contacto']); // Valor del contacto

    // 1. Insertar detalle del documento
    $sqlDocumento = "INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento) VALUES ($tipoDocumentoId, '$valorDocumento')";
    if (mysqli_query($conection, $sqlDocumento)) {
        $idDetalleDocumento = mysqli_insert_id($conection); // Obtener el ID del detalle de documento

        // 2. Insertar detalle de contacto
        $sqlContacto = "INSERT INTO tb_detalle_contacto (valorDetalleContacto, tipo_contacto_id) VALUES ('$valorContacto', $tipoContactoId)";
        if (mysqli_query($conection, $sqlContacto)) {
            $idDetalleContacto = mysqli_insert_id($conection); // Obtener el ID del detalle de contacto

            // 3. Insertar persona física
            $sql1 = "INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, detalle_documento_id, detalle_contacto_id, estado_persona_id) 
                      VALUES ('$nombres', '$apellidos', '$fechaNacimiento', '$sexo', $idDetalleDocumento, $idDetalleContacto, NULL)";
            if (mysqli_query($conection, $sql1)) {
                $idPersonaFisica = mysqli_insert_id($conection); // Obtener el ID de la persona física

                // 4. Insertar domicilio
                $sqlDomicilio = "INSERT INTO tb_domicilios (descripcionDomicilio) VALUES ('$descripcionDomicilio')";
                if (mysqli_query($conection, $sqlDomicilio)) {
                    $idDomicilio = mysqli_insert_id($conection); // Obtener el ID del domicilio

                    // 5. Insertar persona jurídica con estado activo
                    $estado_persona_juridica_id = 1; // Estado activo por defecto
                    $sql2 = "INSERT INTO tb_personas_juridicas (razonSocial, persona_fisica_id, estado_persona_juridica_id) VALUES ('$razonSocial', $idPersonaFisica, $estado_persona_juridica_id)";
                    if (mysqli_query($conection, $sql2)) {
                        $_SESSION['message'] = 'Proveedor registrado con éxito.';
                        $_SESSION['alert_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'Error al registrar persona jurídica: ' . mysqli_error($conection);
                        $_SESSION['alert_type'] = 'error';
                    }
                } else {
                    $_SESSION['message'] = 'Error al registrar domicilio: ' . mysqli_error($conection);
                    $_SESSION['alert_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = 'Error al registrar persona física: ' . mysqli_error($conection);
                $_SESSION['alert_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = 'Error al registrar contacto: ' . mysqli_error($conection);
            $_SESSION['alert_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Error al registrar documento: ' . mysqli_error($conection);
        $_SESSION['alert_type'] = 'error';
    }

    // Redirigir a la página del formulario
    header("Location: dashboardProveedores.php");
    exit();
}

// Cerrar conexión
mysqli_close($conection);
?>
