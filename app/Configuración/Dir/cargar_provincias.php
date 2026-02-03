<?php
// Conexión a la base de datos
include '../../modelos/conexion.php';

$pais_id = $_GET['pais_id'];

$query = "SELECT idProvincia, nombreProvincia FROM tb_provincias WHERE pais_id = $pais_id";
$result = mysqli_query($conection, $query);

$provincias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $provincias[] = $row;
}

echo json_encode($provincias);
?>
