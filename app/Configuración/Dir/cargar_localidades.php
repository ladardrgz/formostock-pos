<?php
// Conexión a la base de datos
include '../../modelos/conexion.php';

$provincia_id = $_GET['provincia_id'];

$query = "SELECT idLocalidad, nombreLocalidad FROM tb_localidades WHERE provincia_id = $provincia_id";
$result = mysqli_query($conection, $query);

$localidades = [];
while ($row = mysqli_fetch_assoc($result)) {
    $localidades[] = $row;
}

echo json_encode($localidades);
?>
