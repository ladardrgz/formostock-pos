<?php
session_start();
include '../modelos/conexion.php';
$mensaje = '';
$tipoMensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreRol = mysqli_real_escape_string($conection, $_POST['nombreRol']);
    if (!empty($nombreRol)) {
        $checkRolQuery = "SELECT idRol FROM tb_roles WHERE nombreRol = '$nombreRol' AND activoRol = 1";
        $resultCheck = mysqli_query($conection, $checkRolQuery);
        if (mysqli_num_rows($resultCheck) > 0) {
            $mensaje = 'El nombre del rol ya existe. Por favor, elige otro.';
            $tipoMensaje = 'error';
        } else {
            $query = "INSERT INTO tb_roles (nombreRol) VALUES ('$nombreRol')";
            if (mysqli_query($conection, $query)) {
                $mensaje = 'Rol administrativo creado correctamente.';
                $tipoMensaje = 'success';
            } else {
                $mensaje = 'Hubo un problema al crear el rol.';
                $tipoMensaje = 'error';
            }
        }
    } else {
        $mensaje = 'Por favor, ingresa un nombre para el rol.';
        $tipoMensaje = 'error';
    }
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipoMensaje'] = $tipoMensaje;
    header("Location: crearRol.php");
    exit();
}
