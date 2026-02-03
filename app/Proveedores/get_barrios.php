<?php
include '../modelos/conexion.php';

$localidad_id = $_GET['localidad_id'];
$query = "SELECT * FROM tb_barrios WHERE localidad_id = $localidad_id";
$result = mysqli_query($conection, $query);
$barrios = [];

while ($row = mysqli_fetch_assoc($result)) {
    $barrios[] = $row;
}

echo json_encode($barrios);
?>
