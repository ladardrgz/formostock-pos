<?php
include '../modelos/conexion.php';

if (isset($_GET['provincia'])) {
    $provincia = $_GET['provincia'];

    $query = "SELECT idLocalidad, nombreLocalidad FROM tb_localidades WHERE provincia_id = $provincia";
    $result = $conection->query($query);

    $localidades = array();
    while ($row = $result->fetch_assoc()) {
        $localidades[] = $row;
    }

    echo json_encode($localidades);
}
?>
