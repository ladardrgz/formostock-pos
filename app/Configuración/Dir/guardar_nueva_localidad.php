<?php
require_once '../../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreLocalidad = trim($_POST['nombrelocalidad']);
    $provinciaId = (int)$_POST['provincia_id'];

    // Verificar que los campos no estén vacíos
    if (!empty($nombreLocalidad) && !empty($provinciaId)) {
        // Primero, verificar si ya existe una localidad con el mismo nombre en la misma provincia
        $queryCheck = "SELECT idLocalidad FROM tb_localidades WHERE nombreLocalidad = ? AND provincia_id = ?";
        $stmtCheck = $conection->prepare($queryCheck);
        $stmtCheck->bind_param('si', $nombreLocalidad, $provinciaId);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            // Si existe una localidad con el mismo nombre en la misma provincia
            header('Location: frm_tb_localidad.php?message=exists');
        } else {
            // Si no existe, insertar la nueva localidad
            $queryInsert = "INSERT INTO tb_localidades (nombreLocalidad, provincia_id) VALUES (?, ?)";
            $stmtInsert = $conection->prepare($queryInsert);
            $stmtInsert->bind_param('si', $nombreLocalidad, $provinciaId);

            // Ejecutar la consulta
            if ($stmtInsert->execute()) {
                header('Location: frm_tb_localidad.php?message=added');
            } else {
                header('Location: frm_tb_localidad.php?message=error');
            }

            $stmtInsert->close();
        }

        $stmtCheck->close();
    } else {
        header('Location: frm_tb_localidad.php?message=empty');
    }

    $conection->close();
} else {
    header('Location: frm_tb_localidad.php');
    exit;
}
?>
