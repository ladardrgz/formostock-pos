<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye la conexión a la base de datos
include("../modelos/conexion.php");

// Configurar el tipo de respuesta como JSON
header('Content-Type: application/json');

// Iniciar sesión
session_start();

// Verificar que el ID de sucursal esté en la sesión
if (!isset($_SESSION['sucursal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró el ID de sucursal en la sesión.']);
    exit;
}

// Obtener los datos del formulario
$sucursal_id = $_SESSION['sucursal_id'];
$producto_id = isset($_POST['producto_id']) ? (int) $_POST['producto_id'] : null;
$cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : null;

// Verificar que se hayan enviado todos los datos necesarios
if (is_null($producto_id) || is_null($cantidad) || $cantidad <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos o la cantidad es inválida.']);
    exit;
}

// Consulta para verificar si el producto existe en la sucursal
$query_inventario = "SELECT stockSucursal FROM tb_inventario_sucursal WHERE sucursal_id = '$sucursal_id' AND producto_id = '$producto_id'";
$resultado_inventario = mysqli_query($conexion, $query_inventario);

if (!$resultado_inventario) {
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . mysqli_error($conexion)]);
    exit;
}

if (mysqli_num_rows($resultado_inventario) > 0) {
    // El producto ya existe en la sucursal, actualizar el stock
    $row = mysqli_fetch_assoc($resultado_inventario);
    $nuevo_stock = $row['stockSucursal'] + $cantidad;

    $query_actualizar = "UPDATE tb_inventario_sucursal SET stockSucursal = '$nuevo_stock' WHERE sucursal_id = '$sucursal_id' AND producto_id = '$producto_id'";
    if (mysqli_query($conexion, $query_actualizar)) {
        echo json_encode(['status' => 'success', 'message' => 'Stock incrementado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el stock: ' . mysqli_error($conexion)]);
    }
} else {
    // El producto no existe en la sucursal, agregarlo
    $query_insertar = "INSERT INTO tb_inventario_sucursal (sucursal_id, producto_id, stockSucursal) VALUES ('$sucursal_id', '$producto_id', '$cantidad')";
    if (mysqli_query($conexion, $query_insertar)) {
        echo json_encode(['status' => 'success', 'message' => 'Stock agregado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar el stock: ' . mysqli_error($conexion)]);
    }
}
?>
