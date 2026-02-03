<?php
include ("../modelos/conexion.php");

if (isset($_GET['proveedor_id'])) {
    $proveedor_id = intval($_GET['proveedor_id']);
    
    // Consulta para obtener los productos del proveedor
    $sql = "SELECT idProducto, descripcionProducto, precioProducto FROM tb_productos WHERE proveedor_id = $proveedor_id";
    $resultado = mysqli_query($conection, $sql);

    // Verificar si la consulta se ejecuta correctamente
    if (!$resultado) {
        echo json_encode(['error' => 'Error en la consulta SQL']);
        exit;
    }

    // Array para almacenar los productos
    $productos = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $productos[] = $row;
    }

    // Imprimir los productos para verificar si están correctos
    if (empty($productos)) {
        echo json_encode(['error' => 'No se encontraron productos para este proveedor']);
    } else {
        echo json_encode($productos);
    }
} else {
    echo json_encode(['error' => 'ID del proveedor no especificado']);
}
?>
