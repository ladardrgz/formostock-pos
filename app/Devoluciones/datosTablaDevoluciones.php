<?php

function obtenerDevoluciones($inicio, $registros_por_pagina, $search) {
    global $conection;

    // Escapar el término de búsqueda para prevenir inyecciones SQL
    $search = mysqli_real_escape_string($conection, $search);

    $query = "
    SELECT 
        tb_devoluciones.idDevolucion,
        tb_devoluciones.factura_cabecera_id,
        tb_factura_cabecera.idFaCab AS numeroFactura,
        tb_devoluciones.cantidadDevolucion,
        tb_devoluciones.fechaDevolucion,
        tb_devoluciones.motivoDevolucion,
        tb_devoluciones.condicionesDeEntrega,
        tb_devoluciones.tipoDevolucion,
        tb_tipos_notas.tipoNota AS tipoNota,
        tb_estados_logicos.nombreEstLog AS estadoNota  -- Obtenemos el estado de la nota desde tb_notas_personas
    FROM 
        tb_devoluciones
    LEFT JOIN 
        tb_factura_cabecera ON tb_devoluciones.factura_cabecera_id = tb_factura_cabecera.idFaCab
    LEFT JOIN 
        tb_tipos_notas ON tb_devoluciones.tipo_nota_id = tb_tipos_notas.idTipoNota
    LEFT JOIN
        tb_notas_personas ON tb_devoluciones.idDevolucion = tb_notas_personas.devolucion_id  -- Unimos con tb_notas_personas
    LEFT JOIN
        tb_estados_logicos ON tb_notas_personas.estado_nota_id = tb_estados_logicos.idEstLog  -- Obtenemos el estado de la nota
    WHERE 
        tb_devoluciones.motivoDevolucion LIKE '%$search%' 
        OR tb_factura_cabecera.idFaCab LIKE '%$search%'
    LIMIT $inicio, $registros_por_pagina
    ";
    
    // Ejecutar la consulta SQL
    $result = mysqli_query($conection, $query);

    // Inicializar el array de devoluciones
    $devoluciones = [];

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener los datos de las devoluciones
        while ($row = mysqli_fetch_assoc($result)) {
            $devoluciones[] = $row;
        }
    } else {
        // Manejar el error si la consulta falla
        error_log("Error en la consulta: " . mysqli_error($conection));
    }

    return $devoluciones;
}
?>
