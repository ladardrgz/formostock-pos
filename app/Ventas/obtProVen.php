<?php
function obtenerProductos($conection, $buscar = '', $categoria_id = null, $marca_id = null, $filtrar_precio = '', $pagina = 1, $limite = 5)
{
    $offset = ($pagina - 1) * $limite;

    $rangos_precio = [
        '1000' => 'precioProducto <= 1000',
        '5000' => 'precioProducto <= 5000',
        '10000' => 'precioProducto <= 10000',
        '100000' => 'precioProducto <= 100000',
        '150000' => 'precioProducto <= 150000',
        '200000' => 'precioProducto <= 200000',
        '250000' => 'precioProducto <= 250000',
        '500000' => 'precioProducto <= 500000',
        '1000000' => 'precioProducto <= 1000000',
        '5000000' => 'precioProducto <= 5000000',
        '10000000' => 'precioProducto <= 10000000'
    ];

    $sql_total = "SELECT COUNT(*) as total FROM tb_productos WHERE descripcionProducto LIKE '%$buscar%'";

    if ($categoria_id) {
        $sql_total .= " AND categoria_id = $categoria_id";
    }
    if ($marca_id) {
        $sql_total .= " AND marca_id = $marca_id";
    }
    if ($filtrar_precio && isset($rangos_precio[$filtrar_precio])) {
        $sql_total .= " AND " . $rangos_precio[$filtrar_precio];
    }

    $result_total = mysqli_query($conection, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_productos = $row_total['total'];
    $total_paginas = ceil($total_productos / $limite);

    $sql = "SELECT * FROM tb_productos WHERE descripcionProducto LIKE '%$buscar%'";

    if ($categoria_id) {
        $sql .= " AND categoria_id = $categoria_id";
    }
    if ($marca_id) {
        $sql .= " AND marca_id = $marca_id";
    }
    if ($filtrar_precio && isset($rangos_precio[$filtrar_precio])) {
        $sql .= " AND " . $rangos_precio[$filtrar_precio];
    }

    $sql .= " LIMIT $limite OFFSET $offset";
    $result = mysqli_query($conection, $sql);

    return [
        'productos' => mysqli_fetch_all($result, MYSQLI_ASSOC),
        'total_paginas' => $total_paginas,
        'pagina_actual' => $pagina
    ];
}
