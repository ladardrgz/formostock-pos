<?php
include('../../modelos/conexion.php');

// Verificar que los parámetros necesarios estén presentes
if (isset($_GET['id']) && isset($_GET['estado'])) {
    $idPeriodo = intval($_GET['id']);
    $nuevoEstado = $_GET['estado'];

    // Determinar el estado lógico correspondiente
    if ($nuevoEstado === 'activo') {
        $estado_periodo_id = 1; // ID correspondiente a "Activo"
    } elseif ($nuevoEstado === 'inactivo') {
        $estado_periodo_id = 2; // ID correspondiente a "Inactivo"
    } else {
        // Si el estado proporcionado no es válido, mostrar un mensaje de error
        echo json_encode([
            'success' => false,
            'message' => 'Estado no válido. Use "activo" o "inactivo".'
        ]);
        exit;
    }

    // Preparar la consulta para actualizar el estado del período
    $query = "UPDATE tb_periodos SET estado_periodo_id = ? WHERE idPeriodo = ?";

    // Usar sentencias preparadas para evitar inyección SQL
    if ($stmt = $conection->prepare($query)) {
        $stmt->bind_param('ii', $estado_periodo_id, $idPeriodo);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'El estado del período se ha actualizado correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el estado del período.'
            ]);
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al preparar la consulta.'
        ]);
    }
} else {
    // Si faltan parámetros, mostrar un mensaje de error
    echo json_encode([
        'success' => false,
        'message' => 'Parámetros insuficientes. Se requiere "id" y "estado".'
    ]);
}
?>
