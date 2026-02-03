<?php
function obtenerFacturas($inicio, $registros_por_pagina, $search)
{
    global $conection;
    $search = mysqli_real_escape_string($conection, $search);

    $query = "
    SELECT 
        tb_factura_cabecera.idFaCab,
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
        tb_detalle_documento.valorDocumento AS numeroDocumento,
        tb_factura_cabecera.fechaDeEmisionFaCab,
        tb_factura_cabecera.fechaDeVencimientoFaCab,
        tb_factura_cabecera.montoTotalFaCab,
        tb_formas_pago.nombreFormaPago,
        tb_estados_logicos.nombreEstLog
    FROM 
        tb_factura_cabecera
    LEFT JOIN 
        tb_clientes ON tb_factura_cabecera.cliente_id = tb_clientes.idCliente
    LEFT JOIN 
        tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN 
        tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    LEFT JOIN 
        tb_formas_pago ON tb_factura_cabecera.forma_pago_id = tb_formas_pago.idFormaPago
    LEFT JOIN 
        tb_estados_logicos ON tb_factura_cabecera.estado_factura_id = tb_estados_logicos.idEstLog
    WHERE 
        tb_personas_fisicas.nombres LIKE '%$search%' 
        OR tb_personas_fisicas.apellidos LIKE '%$search%'
        OR tb_detalle_documento.valorDocumento LIKE '%$search%'
    LIMIT $inicio, $registros_por_pagina
    ";
    $result = mysqli_query($conection, $query);

    $facturas = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $facturas[] = $row;
        }
    } else {
        error_log("Error en la consulta: " . mysqli_error($conection));
    }

    return $facturas;
}
