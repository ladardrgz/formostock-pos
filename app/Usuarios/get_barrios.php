<?php
include '../modelos/conexion.php';

$localidad_id = $_GET['localidad_id'];

$sql_barrios = "SELECT idBarrio, nombreBarrio FROM tb_barrios WHERE localidad_id = ?";
$stmt = $conection->prepare($sql_barrios);
$stmt->bind_param('i', $localidad_id);
$stmt->execute();
$result = $stmt->get_result();

$barrios = array();
while($row = $result->fetch_assoc()) {
    $barrios[] = $row;
}

echo json_encode($barrios);
?>
