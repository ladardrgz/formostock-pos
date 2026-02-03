<?php
include '../modelos/conexion.php';

$provincia_id = $_GET['provincia_id'];

$sql_localidades = "SELECT idLocalidad, nombreLocalidad FROM tb_localidades WHERE provincia_id = ?";
$stmt = $conection->prepare($sql_localidades);
$stmt->bind_param("i", $provincia_id);
$stmt->execute();
$result = $stmt->get_result();

$localidades = array();
while ($row = $result->fetch_assoc()) {
    $localidades[] = $row;
}

echo json_encode($localidades);

$stmt->close();
$conection->close();
?>
