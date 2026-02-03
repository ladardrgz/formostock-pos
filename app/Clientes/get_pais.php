<?php
include '../modelos/conexion.php';

// Consulta para obtener todos los países
$sql_paises = "SELECT idPais, nombrePais FROM tb_paises";
if ($result = $conection->query($sql_paises)) {
    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        $paises = array();
        while ($row = $result->fetch_assoc()) {
            $paises[] = $row;
        }
        echo json_encode($paises);
    } else {
        echo json_encode(array());
    }

    $result->free();
} else {
    echo json_encode(array('error' => 'Error al realizar la consulta: ' . $conection->error));
}

$conection->close();
?>
