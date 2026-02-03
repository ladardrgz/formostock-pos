<?php
include '../modelos/conexion.php';

function obtenerImpuestos($conection, $idProducto) {
    $sql = "
        SELECT di.valorDetalleImpuesto 
        FROM tb_productos p
        LEFT JOIN tb_detalle_impuestos di ON p.impuesto_id = di.idDetalleImpuesto
        WHERE p.idProducto = $idProducto
    ";
    
    $result = mysqli_query($conection, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['valorDetalleImpuesto'];
    } else {
        return 0; 
    }
}
?>
