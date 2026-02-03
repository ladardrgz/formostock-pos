<?php
// Incluir el archivo de conexión
require_once '../modelos/conexion.php';

// Verificar si se recibe el ID de la caja como parámetro
if (isset($_POST['idCaja'])) {
    // Obtener el ID de la caja desde la solicitud
    $idCaja = $_POST['idCaja'];

    // Verificar que la conexión sea exitosa
    if ($conection) {
        // Preparar la consulta para actualizar el estado de la caja
        $sql = "UPDATE tb_caja 
                SET estado_caja_id = 41, fechaCierreCaja = NOW() 
                WHERE idCaja = ?";

        $stmt = mysqli_prepare($conection, $sql);

        if ($stmt) {
            // Vincular parámetros
            mysqli_stmt_bind_param($stmt, "i", $idCaja);

            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                // Respuesta de éxito
                echo json_encode([
                    'status' => 'success',
                    'message' => 'La caja ha sido cerrada exitosamente.'
                ]);
            } else {
                // Respuesta de error al ejecutar
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al cerrar la caja. Por favor, intente de nuevo.'
                ]);
            }

            // Cerrar el statement
            mysqli_stmt_close($stmt);
        } else {
            // Respuesta de error al preparar la consulta
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al preparar la consulta.'
            ]);
        }

        // Cerrar la conexión
        mysqli_close($conection);
    } else {
        // Respuesta de error en la conexión
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la conexión a la base de datos.'
        ]);
    }
} else {
    // Respuesta si no se recibe el ID de la caja
    echo json_encode([
        'status' => 'error',
        'message' => 'No se proporcionó un ID de caja válido.'
    ]);
}
?>
