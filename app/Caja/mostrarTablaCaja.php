<?php
function obtenerCajas($inicio, $registros_por_pagina, $search) {
    global $conection;

    // Escapar el término de búsqueda para prevenir inyecciones SQL
    $search = mysqli_real_escape_string($conection, $search);

    // Consulta SQL para obtener los datos de las cajas, incluyendo la cuenta de usuario y el estado
    $query = "
    SELECT 
        tb_caja.idCaja,
        tb_caja.nombreCaja,
        tb_caja.saldoInicialCaja,
        tb_caja.saldoActualCaja,
        tb_caja.fechaAperturaCaja,
        tb_caja.fechaCierreCaja,
        tb_caja.estado_caja_id,
        tb_estados_logicos.nombreEstLog
    FROM 
        tb_caja
    LEFT JOIN 
        tb_estados_logicos ON tb_caja.estado_caja_id = tb_estados_logicos.idEstLog
    WHERE 
        tb_caja.nombreCaja LIKE '%$search%'
    LIMIT $inicio, $registros_por_pagina
    ";

    // Ejecutar la consulta SQL
    $result = mysqli_query($conection, $query);

    // Inicializar el array de cajas
    $cajas = [];

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener los datos de las cajas
        while ($row = mysqli_fetch_assoc($result)) {
            $cajas[] = $row;
        }
    } else {
        // Manejar el error si la consulta falla
        error_log("Error en la consulta: " . mysqli_error($conection));
    }    

    return $cajas;
}
?>
