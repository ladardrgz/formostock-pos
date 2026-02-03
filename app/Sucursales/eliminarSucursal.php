<?php
include '../modelos/conexion.php';

$idSucursal = isset($_POST['idSucursal']) ? (int)$_POST['idSucursal'] : 0;

$response = ['success' => false, 'message' => ''];

if ($idSucursal <= 0) {
    $response['message'] = 'ID de sucursal inválido.';
    echo json_encode($response);
    exit;
}

// Consulta para eliminar la sucursal
$query = "DELETE FROM tb_sucursal WHERE idSucursal = ?";
$stmt = $conection->prepare($query);
$stmt->bind_param("i", $idSucursal);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Sucursal eliminada exitosamente.';
    } else {
        $response['message'] = 'No se encontró la sucursal con el ID proporcionado.';
    }
} else {
    $response['message'] = 'Error al eliminar la sucursal.';
}

$stmt->close();
$conection->close();

echo json_encode($response);
?>
