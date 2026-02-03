<?php
function obtenerUsuarios($inicio, $registros_por_pagina, $search = '', $role_filter = '')
{
    global $conection;
    include_once '../modelos/conexion.php';

    // Preparar la consulta SQL para buscar usuarios con LIMIT y OFFSET para la paginación
    $query = "
    SELECT 
        tb_usuarios.idUsuario, 
        tb_usuarios.nombreCuentaUsuario AS nombre_usuario, 
        tb_usuarios.emailUsuario AS correo, 
        tb_roles.nombreRol AS rol, 
        tb_personas_fisicas.nombres AS nombre, 
        tb_personas_fisicas.apellidos AS apellido, 
        tb_estados_logicos.nombreEstLog AS estado, 
        tb_detalle_documento.valorDocumento AS documento, 
        tb_tipo_documentos.nombreTipoDoc AS tipo_documento
    FROM 
        tb_usuarios 
    INNER JOIN 
        tb_roles ON tb_usuarios.rol_id = tb_roles.idRol
    INNER JOIN 
        tb_personas_fisicas ON tb_usuarios.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    INNER JOIN 
        tb_estados_logicos ON tb_usuarios.estado_usuario_id = tb_estados_logicos.idEstLog
    INNER JOIN 
        tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    INNER JOIN 
        tb_tipo_documentos ON tb_detalle_documento.tipo_documento_id = tb_tipo_documentos.idTipoDocumento
    WHERE 
        tb_estados_logicos.nombreEstLog = 'Activo'
    ";

    // Añadir condiciones de búsqueda
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conection, $search);
        $query .= " AND (tb_usuarios.nombreCuentaUsuario LIKE '%$search%' 
                        OR tb_usuarios.emailUsuario LIKE '%$search%' 
                        OR CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) LIKE '%$search%' 
                        OR tb_detalle_documento.valorDocumento LIKE '%$search%')";
    }

    // Añadir filtro por rol
    if (!empty($role_filter)) {
        $role_filter = mysqli_real_escape_string($conection, $role_filter);
        $query .= " AND tb_roles.idRol = '$role_filter'";
    }

    // Añadir límites de paginación
    $query .= " LIMIT $inicio, $registros_por_pagina";

    $result = mysqli_query($conection, $query);

    $usuarios = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conection);

    return $usuarios;
}
