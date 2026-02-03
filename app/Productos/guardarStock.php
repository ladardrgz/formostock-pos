<?php
session_start();
include '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProducto = intval($_POST['idProducto']);
    $stocks = $_POST['stock']; // Array de stocks por sucursal

    foreach ($stocks as $sucursal_id => $stockSucursal) {
        $stockSucursal = intval($stockSucursal);

        if ($stockSucursal > 0) { // Solo insertar si el stock es mayor que 0
            $sql = $conection->prepare(
                "INSERT INTO tb_inventario_sucursal (sucursal_id, producto_id, stockSucursal) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE stockSucursal = ?"
            );

            $sql->bind_param("iiii", $sucursal_id, $idProducto, $stockSucursal, $stockSucursal);

            if (!$sql->execute()) {
                $_SESSION['mensaje'] = 'Error al registrar stock: ' . $conection->error;
                header("Location: RegistrarStock.php?idProducto=$idProducto"); // Redirigir
                exit;
            }
        }
    }

    $_SESSION['mensaje'] = 'Registro exitoso';
    header('Location: dashboardProductos.php'); // Redirigir a la página principal o donde sea necesario
    exit;
}

$conection->close();
?>
