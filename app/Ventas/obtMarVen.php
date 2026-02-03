<?php
include '../modelos/conexion.php';
function obtenerMarcas($conection) {
    $query = "SELECT * FROM tb_marcas_productos";
    $result = mysqli_query($conection, $query);
    $marcas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $marcas[] = $row;
    }
    return $marcas;
}
