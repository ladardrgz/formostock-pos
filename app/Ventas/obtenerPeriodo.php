<?php
header('Content-Type: application/json');
require '../modelos/conexion.php'; 

$data = json_decode(file_get_contents('php://input'), true);
$fechaActual = $data['fechaActual'];

try {
    $sql = "SELECT idPeriodo FROM tb_periodos 
            WHERE :fechaActual BETWEEN fechaInicioPeriodo AND fechaFinPeriodo 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fechaActual', $fechaActual, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'idPeriodo' => $result['idPeriodo']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró un período.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al consultar el período: ' . $e->getMessage()]);
}
?>
