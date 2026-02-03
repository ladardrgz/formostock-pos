<?php
function obtenerClientes($inicio, $registros_por_pagina) {
    // Incluir la conexión a la base de datos
    include_once '../modelos/conexion.php';


    // Obtener el término de búsqueda y escaparlo para evitar inyecciones SQL
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conection, $_GET['search']) : '';

    // Consulta para obtener los clientes con la condición de estado_persona_id = 1
    $query = "
        SELECT 
            tb_clientes.idCliente AS 'Número de cliente',
            tb_detalle_documento.valorDocumento AS 'Documento del cliente',
            CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS 'Nombre Completo',
            tb_personas_fisicas.fechaNacimiento AS 'Fecha de nacimiento',
            tb_personas_fisicas.sexo AS 'Sexo',
            tb_detalle_contacto.valorDetalleContacto AS 'Información de contacto',
            CONCAT(
                IFNULL(tb_domicilios_personas.valorDomicilio, ''), ', ',
                IFNULL(tb_barrios.nombreBarrio, ''), ', ',
                IFNULL(tb_localidades.nombreLocalidad, ''), ', ',
                IFNULL(tb_provincias.nombreProvincia, ''), ', ',
                IFNULL(tb_paises.nombrePais, '')
            ) AS 'Domicilio'
        FROM 
            tb_clientes
        INNER JOIN 
            tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
        LEFT JOIN 
            tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
        LEFT JOIN 
            tb_detalle_contacto ON tb_personas_fisicas.detalle_contacto_id = tb_detalle_contacto.idDetalleContacto
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
            (tb_personas_fisicas.nombres LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
                OR tb_personas_fisicas.apellidos LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
                OR tb_detalle_documento.valorDocumento LIKE '%" . mysqli_real_escape_string($conection, $search) . "%')
            AND tb_personas_fisicas.estado_persona_id = 1
        LIMIT $inicio, $registros_por_pagina
    ";

    // Ejecutar la consulta
    $result = mysqli_query($conection, $query);

    // Verificar si la consulta se ejecutó correctamente
    if (!$result) {
        die('Error en la consulta: ' . mysqli_error($conection));
    }

    // Inicializar el array para almacenar los resultados
    $clientes = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $clientes[] = $row;
        }
    }

    // Retornar los clientes obtenidos
    return $clientes;
}
?>
