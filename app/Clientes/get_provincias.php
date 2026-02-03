<?php
include '../modelos/conexion.php';

$pais_id = $_GET['pais_id'];

$sql_provincias = "SELECT idProvincia, nombreProvincia FROM tb_provincias WHERE pais_id = ?";
$stmt = $conection->prepare($sql_provincias);
$stmt->bind_param("i", $pais_id);
$stmt->execute();
$result = $stmt->get_result();

$provincias = array();
while ($row = $result->fetch_assoc()) {
    $provincias[] = $row;
}

echo json_encode($provincias);

$stmt->close();
$conection->close();
?>
