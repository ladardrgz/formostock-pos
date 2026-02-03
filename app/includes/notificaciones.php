<?php
session_start();
require_once '../modelos/conexion.php';

if (!isset($_SESSION['sucursal_id'])) {
    echo json_encode(['error' => 'Código de sucursal no establecido']);
    exit;
}

$sucursal_id = $_SESSION['sucursal_id'];

$query = "
    SELECT 
    productos.descripcionProducto AS descripcion, 
    inventario.stockSucursal AS stockActual, 
    productos.stockMinProducto AS stockMinimo 
    FROM tb_productos AS productos
    JOIN tb_inventario_sucursal AS inventario ON productos.idProducto = inventario.producto_id
    WHERE inventario.sucursal_id = ? AND inventario.stockSucursal <= productos.stockMinProducto
";

$stmt = mysqli_prepare($conection, $query);

if (!$stmt) {
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $sucursal_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode(['error' => 'Error en la ejecución de la consulta']);
    exit;
}

$notificaciones = [];

while ($row = mysqli_fetch_assoc($result)) {
    $descripcionProducto = $row['descripcion'];
    $stockActual = $row['stockActual'];
    $stockMinimo = $row['stockMinimo'];

    $mensaje = "Bajo en stock $descripcionProducto (Stock actual $stockActual, Mínimo requerido $stockMinimo)";

    $notificaciones[] = ['mensaje' => $mensaje, 'descripcion' => $descripcionProducto, 'stockActual' => $stockActual, 'stockMinimo' => $stockMinimo];
}

header('Content-Type: application/json');

echo json_encode($notificaciones);
