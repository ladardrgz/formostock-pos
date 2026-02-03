<?php
function obtenerOrdenes($inicio, $registros_por_pagina, $search) {
    global $conection;

    // Escapar el término de búsqueda para evitar inyecciones SQL
    $search = mysqli_real_escape_string($conection, $search);

    // Consulta SQL ajustada para obtener la información de las órdenes de compra
    $query = "
    SELECT 
        oc.idOrdenCompra, 
        oc.fechaOrden, 
        pj.razonSocial AS proveedor,
        oc.totalOrdenCompra, 
        el.nombreEstLog AS estadoOrden
    FROM 
        tb_ordenes_compra AS oc
    LEFT JOIN 
        tb_personas_juridicas AS pj ON oc.proveedor_id = pj.idPersonaJuridica
    LEFT JOIN 
        tb_estados_logicos AS el ON oc.estado_orden_id = el.idEstLog
    WHERE 
        pj.razonSocial LIKE '%$search%'  -- Buscar por nombre de proveedor
    OR 
        oc.fechaOrden LIKE '%$search%'   -- Buscar por fecha de orden
    LIMIT $inicio, $registros_por_pagina
    ";

    // Ejecutar la consulta SQL
    $result = mysqli_query($conection, $query);

    // Inicializar el array de órdenes
    $ordenes = [];

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener los datos de las órdenes de compra
        while ($row = mysqli_fetch_assoc($result)) {
            $ordenes[] = $row;
        }
    } else {
        // Manejar el error si la consulta falla
        error_log("Error en la consulta: " . mysqli_error($conection));
    }

    return $ordenes;
}
?>
