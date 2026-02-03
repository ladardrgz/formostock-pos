<?php
include("../modelos/conexion.php");
include 'obtenerProveedor.php';
$proveedor = new Proveedor($conection);
$proveedoresActivos = $proveedor->obtenerProveedoresActivos();

// Verificar si se envió la orden de compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $fechaOrden = $_POST['fechaOrden'];
    $estadoOrdenId = $_POST['estado_orden_id'];
    $proveedorId = $_POST['proveedor_id'];
    $productosAgregados = json_decode($_POST['productosAgregados'], true);
    $totalOrdenCompra = $_POST['totalOrdenCompra'];

    if (!empty($productosAgregados)) {
        // Insertar la orden de compra en la base de datos
        $query = "INSERT INTO tb_ordenes_compra (fechaOrden, proveedor_id, totalOrdenCompra, estado_orden_id)
                  VALUES ('$fechaOrden', $proveedorId, $totalOrdenCompra, $estadoOrdenId)";
        if ($conection->query($query)) {
            $ordenCompraId = $conection->insert_id; // Obtener el ID de la orden insertada

            // Insertar los detalles de la orden de compra
            foreach ($productosAgregados as $producto) {
                $productoId = $producto['idProducto'];
                $cantidad = $producto['cantidad'];
                $precio = $producto['precioProducto'];
                $subTotal = $precio * $cantidad;

                $detalleQuery = "INSERT INTO tb_detalle_orden_compra (orden_compra_id, producto_id, cantidadProducto, precioProducto, subTotalProducto)
                                 VALUES ($ordenCompraId, $productoId, $cantidad, $precio, $subTotal)";
                $conection->query($detalleQuery);
            }

            // Respuesta exitosa
            echo json_encode(["success" => true, "message" => "Orden de compra procesada exitosamente."]);
        } else {
            // Error al insertar la orden
            echo json_encode(["success" => false, "message" => "Error al procesar la orden."]);
        }
    } else {
        // No se agregaron productos
        echo json_encode(["success" => false, "message" => "No se han agregado productos a la orden."]);
    }
}
?>
