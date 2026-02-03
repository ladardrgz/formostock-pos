<?php
include '../modelos/conexion.php';

if (isset($_GET['paisId'])) {
    // Obtener provincias por país
    $paisId = $_GET['paisId'];
    $queryProvincias = "SELECT * FROM tb_provincias WHERE pais_id = $paisId";
    $resultadoProvincias = mysqli_query($conection, $queryProvincias);

    if (mysqli_num_rows($resultadoProvincias) > 0) {
        echo '<option value="">Seleccione una provincia</option>';
        while ($fila = mysqli_fetch_assoc($resultadoProvincias)) {
            echo "<option value='" . $fila['idProvincia'] . "'>" . $fila['nombreProvincia'] . "</option>";
        }
    }
}

if (isset($_GET['provinciaId'])) {
    // Obtener localidades por provincia
    $provinciaId = $_GET['provinciaId'];
    $queryLocalidades = "SELECT * FROM tb_localidades WHERE provincia_id = $provinciaId";
    $resultadoLocalidades = mysqli_query($conection, $queryLocalidades);

    if (mysqli_num_rows($resultadoLocalidades) > 0) {
        echo '<option value="">Seleccione una localidad</option>';
        while ($fila = mysqli_fetch_assoc($resultadoLocalidades)) {
            echo "<option value='" . $fila['idLocalidad'] . "'>" . $fila['nombreLocalidad'] . "</option>";
        }
    }
}

if (isset($_GET['localidadId'])) {
    // Obtener barrios por localidad
    $localidadId = $_GET['localidadId'];
    $queryBarrios = "SELECT * FROM tb_barrios WHERE localidad_id = $localidadId";
    $resultadoBarrios = mysqli_query($conection, $queryBarrios);

    if (mysqli_num_rows($resultadoBarrios) > 0) {
        echo '<option value="">Seleccione un barrio</option>';
        while ($fila = mysqli_fetch_assoc($resultadoBarrios)) {
            echo "<option value='" . $fila['idBarrio'] . "'>" . $fila['nombreBarrio'] . "</option>";
        }
    }
}
?>
