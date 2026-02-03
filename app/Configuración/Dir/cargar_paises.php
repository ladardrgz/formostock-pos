<?php
// Conexión a la base de datos
include '../../modelos/conexion.php';

$query = "SELECT idPais, nombrePais FROM tb_paises";
$result = mysqli_query($conection, $query);

$paises = [];
while ($row = mysqli_fetch_assoc($result)) {
    $paises[] = $row;
}

echo json_encode($paises);
?>
