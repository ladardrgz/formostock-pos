<?php
include '../modelos/conexion.php';

if (isset($_GET['pais'])) {
    $pais = $_GET['pais'];

    $query = "SELECT idProvincia, nombreProvincia FROM tb_provincias WHERE pais_id = $pais";
    $result = $conection->query($query);

    $provincias = array();
    while ($row = $result->fetch_assoc()) {
        $provincias[] = $row;
    }

    echo json_encode($provincias);
}
?>
