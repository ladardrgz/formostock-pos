<?php
include '../modelos/conexion.php';

function obtenerCategorias($conection)
{
    $query = "SELECT * FROM tb_categorias_productos";

    $result = mysqli_query($conection, $query);

    $categorias = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $categorias[] = $row;
    }

    return $categorias;
}
