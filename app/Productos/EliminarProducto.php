<?php
include '../modelos/conexion.php';

$idProducto = isset($_POST['idProducto']) ? (int)$_POST['idProducto'] : 0;

$response = ['success' => false, 'message' => ''];

if ($idProducto <= 0) {
    $response['message'] = 'ID de producto inválido.';
    echo json_encode($response);
    exit;
}

// Iniciar una transacción
$conection->begin_transaction();

try {
    // Consulta para eliminar el registro en inventario por sucursal
    $queryInventario = "DELETE FROM tb_inventario_sucursal WHERE producto_id = ?";
    $stmtInventario = $conection->prepare($queryInventario);
    $stmtInventario->bind_param("i", $idProducto);
    $stmtInventario->execute();
    $stmtInventario->close();

    // Consulta para eliminar el producto
    $queryProducto = "DELETE FROM tb_productos WHERE idProducto = ?";
    $stmtProducto = $conection->prepare($queryProducto);
    $stmtProducto->bind_param("i", $idProducto);
    
    if ($stmtProducto->execute()) {
        $response['success'] = true;
        $response['message'] = 'Producto eliminado exitosamente.';
    } else {
        throw new Exception('Error al eliminar el producto.');
    }

    $stmtProducto->close();

    // Confirmar la transacción
    $conection->commit();
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conection->rollback();
    $response['message'] = $e->getMessage();
}

$conection->close();
echo json_encode($response);
?>
