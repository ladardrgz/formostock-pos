<?php
include '../modelos/conexion.php';

// Obtener la consulta desde el parámetro GET
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Usar la conexión de mysqli
if ($conection) {
    // Preparar la consulta para evitar inyecciones SQL
    $query = mysqli_real_escape_string($conection, '%' . $query . '%');

    // Consulta SQL actualizada para permitir búsqueda por el valor del documento
    $sql = "
        SELECT 
            c.idCliente,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombreCompleto,
            d.valorDocumento AS documento,
            t.nombreTipoDoc AS tipoDocumento
        FROM 
            tb_clientes c
        JOIN 
            tb_personas_fisicas p ON c.persona_fisica_id = p.idPersonaFisica
        JOIN 
            tb_detalle_documento d ON p.detalle_documento_id = d.idDetalleDocumento
        JOIN 
            tb_tipo_documentos t ON d.tipo_documento_id = t.idTipoDocumento
        WHERE 
            (p.nombres LIKE '$query' OR p.apellidos LIKE '$query' OR d.valorDocumento LIKE '$query')
            AND p.estado_persona_id = (SELECT idEstLog FROM tb_estados_logicos WHERE nombreEstLog = 'Activo')
    ";

    // Ejecutar la consulta
    $result = mysqli_query($conection, $sql);
    $clientes = [];

    // Recoger los resultados
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $clientes[] = $row;
        }
    }

    // Devolver los resultados en formato JSON
    echo json_encode($clientes);

    // Cerrar la conexión
    mysqli_close($conection);
} else {
    echo json_encode([]);
}
?>
