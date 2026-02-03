<?php

include_once '../modelos/conexion.php';

// Función para obtener los productos con stock actual
function obtenerProductos($inicio, $registros_por_pagina, $search) {
    global $conection;

    // Obtener la sucursal seleccionada
    $sucursal_id = $_SESSION['sucursal_id'];

    // Escapar el término de búsqueda para evitar inyecciones SQL
    $search = mysqli_real_escape_string($conection, $search);

    // Consulta SQL ajustada para obtener el nombre del impuesto y su valor combinados
    $query = "
        SELECT 
            tb_productos.idProducto, 
            tb_productos.codigoBarrasProducto,
            tb_productos.numeroDeSerieProducto,
            tb_productos.descripcionProducto, 
            tb_productos.precioProducto, 
            tb_productos.garantiaProducto, 
            tb_productos.imagenProducto, 
            tb_productos.impuesto_id,
            CONCAT(tb_tipo_impuestos.nombreImpuesto, ' - ', tb_detalle_impuestos.valorDetalleImpuesto) AS impuestoDetalle, 
            tb_categorias_productos.nombreCategoriaProducto, 
            tb_marcas_productos.nombreMarcaProducto, 
            tb_estados_logicos.nombreEstLog,
            COALESCE(tb_inventario_sucursal.stockSucursal, 0) AS stockSucursal -- Obtener el stock de la sucursal
        FROM 
            tb_productos
        LEFT JOIN 
            tb_detalle_impuestos ON tb_productos.impuesto_id = tb_detalle_impuestos.idDetalleImpuesto
        LEFT JOIN 
            tb_tipo_impuestos ON tb_detalle_impuestos.tipo_impuesto_id = tb_tipo_impuestos.idTipoImpuesto
        LEFT JOIN 
            tb_categorias_productos ON tb_productos.categoria_id = tb_categorias_productos.idCategoriaProducto
        LEFT JOIN 
            tb_marcas_productos ON tb_productos.marca_id = tb_marcas_productos.idMarcaProducto
        LEFT JOIN 
            tb_estados_logicos ON tb_productos.estado_producto_id = tb_estados_logicos.idEstLog
        LEFT JOIN 
            tb_inventario_sucursal ON tb_productos.idProducto = tb_inventario_sucursal.producto_id 
            AND tb_inventario_sucursal.sucursal_id = ?
        WHERE 
            tb_productos.descripcionProducto LIKE '%$search%' 
            OR tb_categorias_productos.nombreCategoriaProducto LIKE '%$search%'
            OR tb_marcas_productos.nombreMarcaProducto LIKE '%$search%'
        LIMIT ?, ?
    ";

    // Preparar la consulta SQL
    $stmt = $conection->prepare($query);
    if (!$stmt) {
        error_log("Error al preparar la consulta: " . mysqli_error($conection));
        return [];
    }

    // Vincular los parámetros y ejecutar la consulta
    $stmt->bind_param("iii", $sucursal_id, $inicio, $registros_por_pagina);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializar el array de productos
    $productos = [];

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener los datos de los productos
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    } else {
        // Manejar el error si la consulta falla
        error_log("Error en la consulta: " . mysqli_error($conection));
    }    

    return $productos;
}
?>
