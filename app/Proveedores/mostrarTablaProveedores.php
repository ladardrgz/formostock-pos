<?php
function obtenerProveedores($inicio, $registros_por_pagina, $search)
{
    global $conection;

    // Escapar el término de búsqueda
    $search = mysqli_real_escape_string($conection, $search);

    // Consulta SQL ajustada para obtener la información de los proveedores y las personas físicas
    $query = "
    SELECT 
        pj.idPersonaJuridica, 
        pj.razonSocial, 
        CONCAT(pf.nombres, ' ', pf.apellidos) AS nombre_completo, 
        dd.valorDocumento AS documento,
        dc.valorDetalleContacto AS contacto,
        pj.estado_persona_juridica_id
    FROM 
        tb_personas_juridicas pj
    LEFT JOIN 
        tb_personas_fisicas pf ON pj.persona_fisica_id = pf.idPersonaFisica
    LEFT JOIN 
        tb_detalle_documento dd ON pf.detalle_documento_id = dd.idDetalleDocumento
    LEFT JOIN 
        tb_detalle_contacto dc ON pf.detalle_contacto_id = dc.idDetalleContacto
    WHERE 
        pj.razonSocial LIKE '%$search%' 
        OR pf.nombres LIKE '%$search%' 
        OR pf.apellidos LIKE '%$search%'
    LIMIT $inicio, $registros_por_pagina
    ";

    // Ejecutar la consulta SQL
    $result = mysqli_query($conection, $query);

    // Inicializar el array de proveedores
    $proveedores = [];

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener los datos de los proveedores
        while ($row = mysqli_fetch_assoc($result)) {
            $proveedores[] = $row;
        }
    } else {
        // Manejar el error si la consulta falla
        error_log("Error en la consulta: " . mysqli_error($conection));
    }

    return $proveedores;
}
