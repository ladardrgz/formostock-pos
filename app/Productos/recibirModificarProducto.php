<?php
include '../modelos/conexion.php';

$idProducto = isset($_POST['idProducto']) ? (int)$_POST['idProducto'] : 0;
$codigoBarras = isset($_POST['codigoBarrasProducto']) ? $_POST['codigoBarrasProducto'] : '';
$numeroSerie = isset($_POST['numeroDeSerieProducto']) ? $_POST['numeroDeSerieProducto'] : '';
$descripcion = isset($_POST['descripcionProducto']) ? $_POST['descripcionProducto'] : '';
$precio = isset($_POST['precioProducto']) ? (float)$_POST['precioProducto'] : 0.0;
$categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
$marca_id = isset($_POST['marca_id']) ? (int)$_POST['marca_id'] : 0;

$query = "UPDATE tb_productos SET 
     codigoBarrasProducto = ?, 
     numeroDeSerieProducto = ?, 
     descripcionProducto = ?, 
     precioProducto = ?, 
     categoria_id = ?, 
     marca_id = ? 
     WHERE idProducto = ?";

// Cambiar la forma de pasar los parámetros
$stmt = $conection->prepare($query);

if ($stmt === false) {
    $response['message'] = 'Error al preparar la consulta: ' . $conection->error;
    echo json_encode($response);
    exit;
}

// Tipo de datos correcto: "s" para strings, "d" para double, "i" para integer
$stmt->bind_param("sssdiii", $codigoBarras, $numeroSerie, $descripcion, $precio, $categoria_id, $marca_id, $idProducto);


if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Producto actualizado exitosamente.';
} else {
    $response['message'] = 'Error al actualizar el producto: ' . $stmt->error;
}

$stmt->close();
$conection->close();

echo json_encode($response);
