<?php
require_once "../../modelos/conexion.php";

$response = array('success' => false, 'message' => '');

if (isset($_GET['id'])) {
    $idProvincia = $_GET['id'];

    // Preparar y ejecutar la consulta para eliminar la provincia
    $queryDeleteProvincia = "DELETE FROM tb_provincias WHERE idProvincia = ?";
    $stmt = $conection->prepare($queryDeleteProvincia);
    $stmt->bind_param('i', $idProvincia);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Provincia eliminada correctamente.";
    } else {
        $response['message'] = "Error al eliminar la provincia: " . $stmt->error;
    }

    $stmt->close();
}

$conection->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
