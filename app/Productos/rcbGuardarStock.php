<?php
session_start();
include '../modelos/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = isset($_POST['idProducto']) ? intval($_POST['idProducto']) : 0;
    $stock = isset($_POST['stock']) ? $_POST['stock'] : [];

    if ($idProducto === 0 || empty($stock)) {
        $_SESSION['mensaje'] = 'Código de producto o cantidad de stock no válidos.';
        header('Location: crearProducto.php'); 
        exit;
    }

    // Preparar una declaración SQL para insertar o actualizar el stock
    $query = "INSERT INTO tb_inventario_sucursal (sucursal_id, producto_id, stockSucursal) VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE stockSucursal = VALUES(stockSucursal)";
    $stmt = $conection->prepare($query);

    // Comenzar una transacción
    $conection->begin_transaction();

    try {
        foreach ($stock as $sucursal_id => $cantidad) {
            $cantidad = intval($cantidad); // Asegurarse de que la cantidad es un entero
            // Ejecutar la consulta
            $stmt->bind_param("iii", $sucursal_id, $idProducto, $cantidad);
            $stmt->execute();
        }
        
        // Confirmar la transacción
        $conection->commit();
        $_SESSION['mensaje'] = 'Stock guardado correctamente.';
    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conection->rollback();
        $_SESSION['mensaje'] = 'Error al guardar el stock: ' . $e->getMessage();
    }

    // Cerrar la declaración
    $stmt->close();
    $conection->close();

    // Redirigir al formulario
    header('Location: crearProducto.php');
    exit;
} else {
    $_SESSION['mensaje'] = 'Método de solicitud no válido.';
    header('Location: crearProducto.php');
    exit;
}
?>
