<?php
session_start();
include '../../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreBarrio = $_POST['nombreBarrio'];
    $localidad_id = $_POST['localidad'];

    // Verificar si ya existe un barrio con el mismo nombre en la misma localidad
    $queryCheck = "SELECT * FROM tb_barrios WHERE nombreBarrio = '$nombreBarrio' AND localidad_id = $localidad_id";
    $resultCheck = mysqli_query($conection, $queryCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        // Si el barrio ya existe, guardar mensaje en la sesión y redirigir
        $_SESSION['message'] = [
            'type' => 'warning',
            'title' => '¡Advertencia!',
            'text' => 'Este barrio ya está en uso. No se puede registrar un barrio con el mismo nombre en esta localidad.'
        ];
        header('Location: frm_tb_barrio.php');
        exit;
    } else {
        // Si no existe, insertar el nuevo barrio
        $queryInsert = "INSERT INTO tb_barrios (nombreBarrio, localidad_id) VALUES ('$nombreBarrio', $localidad_id)";
        if (mysqli_query($conection, $queryInsert)) {
            // Si la inserción fue exitosa, guardar mensaje en la sesión
            $_SESSION['message'] = [
                'type' => 'success',
                'title' => '¡Éxito!',
                'text' => 'El barrio ha sido registrado exitosamente.'
            ];
        } else {
            // Si hubo un error, guardar mensaje en la sesión
            $_SESSION['message'] = [
                'type' => 'error',
                'title' => '¡Error!',
                'text' => 'Hubo un problema al registrar el barrio. Intenta nuevamente.'
            ];
        }
        header('Location: frm_tb_barrio.php');
        exit;
    }
}
?>
