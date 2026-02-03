<?php
include_once '../modelos/conexion.php';

if (isset($_POST['producto_id']) && isset($_POST['numero_serie'])) {
    $productoId = (int)$_POST['producto_id'];
    $numeroSerie = $_POST['numero_serie'];
    $query = "
        SELECT 1 
        FROM tb_productos 
        WHERE idProducto = $productoId 
        AND numeroDeSerieProducto = '$numeroSerie'
    ";
    $result = mysqli_query($conection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['existe' => true]);
    } else {
        echo json_encode(['existe' => false]);
    }
}
?>
