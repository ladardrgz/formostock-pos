<?php
include '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    mysqli_begin_transaction($conection);

    // Recoger y sanitizar los datos del formulario
    $nombre_cuenta = mysqli_real_escape_string($conection, $_POST['nombreCuentaUsuario']);
    $contrasena = md5(mysqli_real_escape_string($conection, $_POST['contraseñaUsuario']));
    $email = mysqli_real_escape_string($conection, $_POST['emailUsuario']);
    $nombres = mysqli_real_escape_string($conection, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conection, $_POST['apellidos']);
    $fecha_nacimiento = mysqli_real_escape_string($conection, $_POST['fechaNacimiento']);
    $sexo = mysqli_real_escape_string($conection, $_POST['sexo']);
    $tipo_documento_id = mysqli_real_escape_string($conection, $_POST['tipo_documento_id']);
    $valor_documento = mysqli_real_escape_string($conection, $_POST['valorDocumento']);
    $tipo_contacto_id = mysqli_real_escape_string($conection, $_POST['tipo_contacto_id']);
    $valor_detalle_contacto = mysqli_real_escape_string($conection, $_POST['valorDetalleContacto']);
    $rol_id = mysqli_real_escape_string($conection, $_POST['rol_id']);
    $estado_usuario_id = 1;
    $estado_persona_id = 1;
    $pais_id = mysqli_real_escape_string($conection, $_POST['pais']);
    $provincia_id = mysqli_real_escape_string($conection, $_POST['provincia']);
    $localidad_id = mysqli_real_escape_string($conection, $_POST['localidad']);
    $barrio_id = mysqli_real_escape_string($conection, $_POST['barrio']);
    $descripcion_domicilio = mysqli_real_escape_string($conection, $_POST['descripcionDomicilio']);

    // Comprobación de duplicados antes de insertar
    $consulta_existencia_documento = "SELECT * FROM tb_detalle_documento WHERE valorDocumento = '$valor_documento'";
    $resultado_existencia_documento = mysqli_query($conection, $consulta_existencia_documento);

    $consulta_existencia_contacto = "SELECT * FROM tb_detalle_contacto WHERE valorDetalleContacto = '$valor_detalle_contacto'";
    $resultado_existencia_contacto = mysqli_query($conection, $consulta_existencia_contacto);

    $consulta_existencia_usuario = "SELECT * FROM tb_usuarios WHERE nombreCuentaUsuario = '$nombre_cuenta'";
    $resultado_existencia_usuario = mysqli_query($conection, $consulta_existencia_usuario);

    $consulta_existencia_email = "SELECT * FROM tb_usuarios WHERE emailUsuario = '$email'";
    $resultado_existencia_email = mysqli_query($conection, $consulta_existencia_email);

    if (mysqli_num_rows($resultado_existencia_documento) > 0) {
        $response = ['status' => 'error', 'message' => "Este documento ya existe en la base de datos."];
    } elseif (mysqli_num_rows($resultado_existencia_contacto) > 0) {
        $response = ['status' => 'error', 'message' => "Este contacto ya existe en la base de datos."];
    } elseif (mysqli_num_rows($resultado_existencia_usuario) > 0) {
        $response = ['status' => 'error', 'message' => "Este nombre de cuenta ya existe en la base de datos."];
    } elseif (mysqli_num_rows($resultado_existencia_email) > 0) {
        $response = ['status' => 'error', 'message' => "Este correo electrónico ya está en uso."];
    } else {
        // Insertar detalle del documento
        $insertar_detalle_documento = "INSERT INTO tb_detalle_documento (tipo_documento_id, valorDocumento) VALUES ('$tipo_documento_id', '$valor_documento')";
        if (mysqli_query($conection, $insertar_detalle_documento)) {
            $id_detalle_documento = mysqli_insert_id($conection); // Obtener el ID del detalle documento insertado

            // Insertar detalle del contacto
            $insertar_detalle_contacto = "INSERT INTO tb_detalle_contacto (valorDetalleContacto, tipo_contacto_id) VALUES ('$valor_detalle_contacto', '$tipo_contacto_id')";
            if (mysqli_query($conection, $insertar_detalle_contacto)) {
                $id_detalle_contacto = mysqli_insert_id($conection); // Obtener el ID del detalle contacto insertado

                // Insertar persona física
                $insertar_persona_fisica = "INSERT INTO tb_personas_fisicas (nombres, apellidos, fechaNacimiento, sexo, estado_persona_id, detalle_documento_id, detalle_contacto_id) VALUES ('$nombres', '$apellidos', '$fecha_nacimiento', '$sexo', '$estado_persona_id', '$id_detalle_documento', '$id_detalle_contacto')";
                if (mysqli_query($conection, $insertar_persona_fisica)) {
                    $id_persona_fisica = mysqli_insert_id($conection); // Obtener el ID de la persona física insertada

                    // Insertar usuario
                    $insertar_usuario = "INSERT INTO tb_usuarios (nombreCuentaUsuario, contraseñaUsuario, emailUsuario, rol_id, estado_usuario_id, persona_fisica_id) VALUES ('$nombre_cuenta', '$contrasena', '$email', '$rol_id', '$estado_usuario_id', '$id_persona_fisica')";
                    if (mysqli_query($conection, $insertar_usuario)) {

                        // Insertar domicilio
                        $insertar_domicilio = "INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id) VALUES ('$descripcion_domicilio', '$barrio_id')";
                        if (mysqli_query($conection, $insertar_domicilio)) {
                            $id_domicilio = mysqli_insert_id($conection); // Obtener el ID del domicilio insertado

                            // Relacionar persona física con domicilio
                            $insertar_domicilio_persona = "INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id) VALUES ('$descripcion_domicilio', '$id_persona_fisica', '$id_domicilio')";
                            if (mysqli_query($conection, $insertar_domicilio_persona)) {
                                $response = ['status' => 'success', 'message' => "Usuario creado exitosamente."];
                            } else {
                                $response = ['status' => 'error', 'message' => "Error al relacionar el domicilio: " . mysqli_error($conection)];
                            }

                        } else {
                            $response = ['status' => 'error', 'message' => "Error al insertar el domicilio: " . mysqli_error($conection)];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => "Error al crear el usuario: " . mysqli_error($conection)];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => "Error al crear la persona física: " . mysqli_error($conection)];
                }
            } else {
                $response = ['status' => 'error', 'message' => "Error al crear el detalle de contacto: " . mysqli_error($conection)];
            }
        } else {
            $response = ['status' => 'error', 'message' => "Error al crear el detalle del documento: " . mysqli_error($conection)];
        }
    }

    // Finalizar la transacción según si hubo error o éxito
    if ($response['status'] === 'error') {
        mysqli_rollback($conection); // Deshacer cambios en caso de error
    } else {
        mysqli_commit($conection); // Confirmar la transacción
    }

    // Cerrar la conexión
    mysqli_close($conection);

    // Devolver la respuesta JSON
    echo json_encode($response);
}
?>
