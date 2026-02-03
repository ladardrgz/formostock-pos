<?php
header('Content-Type: application/json');

// Conectar a la base de datos
include_once '../modelos/conexion.php'; // Asegúrate de que esta ruta sea correcta

function eliminarCliente($idCliente) {
    global $conection;

    // Escapar el ID del cliente para evitar inyecciones SQL
    $idCliente = mysqli_real_escape_string($conection, $idCliente);

    // Consulta para obtener el ID del estado lógico "Inactivo"
    $query_estado_inactivo = "SELECT idEstLog FROM tb_estados_logicos WHERE nombreEstLog = 'Inactivo'";
    $result_estado_inactivo = mysqli_query($conection, $query_estado_inactivo);

    if (!$result_estado_inactivo) {
        return array('success' => false, 'message' => 'Error en la consulta para obtener el estado lógico: ' . mysqli_error($conection));
    }

    // Obtener el ID del estado lógico "Inactivo"
    $row_estado_inactivo = mysqli_fetch_assoc($result_estado_inactivo);
    $estado_inactivo_id = $row_estado_inactivo['idEstLog'];

    // Consulta para actualizar el estado del cliente a "Inactivo"
    $query_update_cliente = "
        UPDATE tb_personas_fisicas
        SET estado_persona_id = $estado_inactivo_id
        WHERE idPersonaFisica = (
            SELECT persona_fisica_id 
            FROM tb_clientes 
            WHERE idCliente = $idCliente
        )
    ";

    // Ejecutar la consulta
    $result_update_cliente = mysqli_query($conection, $query_update_cliente);

    if (!$result_update_cliente) {
        return array('success' => false, 'message' => 'Error al actualizar el estado del cliente: ' . mysqli_error($conection));
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conection);

    return array('success' => true, 'message' => 'Cliente actualizado a Inactivo con éxito.');
}

// Validar y sanitizar el ID del cliente recibido
$idCliente = isset($_POST['idCliente']) ? intval($_POST['idCliente']) : 0;

if ($idCliente > 0) {
    $response = eliminarCliente($idCliente);
} else {
    $response = array('success' => false, 'message' => 'ID de cliente no válido.');
}

echo json_encode($response);
?>
