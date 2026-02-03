<?php
session_start();
include_once '../modelos/conexion.php';

if (!isset($_SESSION['sucursal_id'])) {
    header("Location: guardarSucursalSession.php");
    exit();
}

if (isset($_GET['producto_id']) && isset($_GET['cantidad_aumentar'])) {
    $producto_id = (int)$_GET['producto_id'];
    $cantidad_aumentar = (int)$_GET['cantidad_aumentar'];

    if ($cantidad_aumentar <= 0) {
        $_SESSION['mensaje'] = "La cantidad a aumentar debe ser mayor que 0.";
        header("Location: dashboardProductos.php");
        exit();
    }
    $sucursal_id = $_SESSION['sucursal_id'];
    $query = "SELECT stockSucursal FROM tb_inventario_sucursal WHERE producto_id = $producto_id AND sucursal_id = $sucursal_id";
    $result = mysqli_query($conection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
        $stock_actual = $producto['stockSucursal'];

        $nuevo_stock = $stock_actual + $cantidad_aumentar;

        $update_query = "UPDATE tb_inventario_sucursal SET stockSucursal = $nuevo_stock WHERE producto_id = $producto_id AND sucursal_id = $sucursal_id";

        if (mysqli_query($conection, $update_query)) {
            $_SESSION['mensaje'] = "El stock se ha aumentado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Hubo un error al actualizar el stock.";
        }
    } else {
        $_SESSION['mensaje'] = "Producto no encontrado en la sucursal.";
    }
} else {
    $_SESSION['mensaje'] = "Datos faltantes para actualizar el stock.";
}

header("Location: dashboardProductos.php");
exit();
