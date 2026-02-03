<?php
include '../modelos/conexion.php';

if (isset($_GET['provincia'])) {
    $provincia = $_GET['provincia'];

    // Consulta para obtener las localidades de la provincia seleccionada
    $queryLocalidades = "SELECT idLocalidad, nombreLocalidad FROM tb_localidades WHERE provincia_id = $provincia";
    $resultLocalidades = $conection->query($queryLocalidades);

    // Consulta para obtener los barrios de la provincia seleccionada
    $queryBarrios = "SELECT idBarrio, nombreBarrio FROM tb_barrios WHERE provincia_id = $provincia";
    $resultBarrios = $conection->query($queryBarrios);

    $response = array(
        'localidades' => array(),
        'barrios' => array()
    );

    // Obtener las localidades de la consulta y agregarlas al array de respuesta
    while ($rowLocalidad = $resultLocalidades->fetch_assoc()) {
        $response['localidades'][] = $rowLocalidad;
    }

    // Obtener los barrios de la consulta y agregarlas al array de respuesta
    while ($rowBarrio = $resultBarrios->fetch_assoc()) {
        $response['barrios'][] = $rowBarrio;
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>
