<?php
function obtenerSucursales($inicio, $registros_por_pagina) {
    global $conection; 

    // Obtener el término de búsqueda, si está presente en la solicitud GET
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conection, $_GET['search']) : '';

    // Preparar la consulta SQL para buscar sucursales con valores nulos en algún atributo
    $query = "
        SELECT 
            tb_sucursal.idSucursal, 
            tb_sucursal.nombreSucursal AS tb_sucursal_nombre, 
            CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS tb_personas_fisicas_responsable, 
            CONCAT(tb_domicilios.descripcionDomicilio, ', ', tb_barrios.nombreBarrio, ', ', tb_localidades.nombreLocalidad, ', ', tb_provincias.nombreProvincia, ', ', tb_paises.nombrePais) AS tb_domicilios_direccion
        FROM 
            tb_sucursal
        LEFT JOIN 
            tb_personas_juridicas ON tb_sucursal.persona_juridica_id = tb_personas_juridicas.idPersonaJuridica
        LEFT JOIN 
            tb_personas_fisicas ON tb_personas_juridicas.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
        LEFT JOIN 
            tb_domicilios_personas ON tb_personas_fisicas.idPersonaFisica = tb_domicilios_personas.persona_fisica_id
        LEFT JOIN 
            tb_domicilios ON tb_domicilios_personas.domicilio_id = tb_domicilios.idDomicilio
        LEFT JOIN 
            tb_barrios ON tb_domicilios.barrio_id = tb_barrios.idBarrio
        LEFT JOIN 
            tb_localidades ON tb_barrios.localidad_id = tb_localidades.idLocalidad
        LEFT JOIN 
            tb_provincias ON tb_localidades.provincia_id = tb_provincias.idProvincia
        LEFT JOIN 
            tb_paises ON tb_provincias.pais_id = tb_paises.idPais
        WHERE 
            (tb_sucursal.nombreSucursal LIKE ? OR
            tb_sucursal.nombreSucursal IS NULL OR
            tb_personas_juridicas.razonSocial IS NULL OR
            tb_personas_fisicas.nombres IS NULL OR
            tb_personas_fisicas.apellidos IS NULL OR
            tb_domicilios.descripcionDomicilio IS NULL OR
            tb_barrios.nombreBarrio IS NULL OR
            tb_localidades.nombreLocalidad IS NULL OR
            tb_provincias.nombreProvincia IS NULL OR
            tb_paises.nombrePais IS NULL)
        LIMIT ?, ?
    ";

    // Preparar la consulta
    $stmt = mysqli_prepare($conection, $query);

    // Crear el término de búsqueda con comodines
    $search_param = "%$search%";

    // Enlazar los parámetros
    mysqli_stmt_bind_param($stmt, "sii", $search_param, $inicio, $registros_por_pagina);

    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $result = mysqli_stmt_get_result($stmt);

    $sucursales = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sucursales[] = $row;
        }
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);

    return $sucursales;
}
?>
