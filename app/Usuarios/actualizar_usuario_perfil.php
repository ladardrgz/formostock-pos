<?php
session_start();
require '../modelos/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['idUsuario'];
    $nombreCompleto = explode(' ', trim($_POST['nombre']));
    $nombres = mysqli_real_escape_string($conection, $nombreCompleto[0]);
    $apellidos = mysqli_real_escape_string($conection, isset($nombreCompleto[1]) ? $nombreCompleto[1] : '');
    $fechaNacimiento = mysqli_real_escape_string($conection, $_POST['fecha-nacimiento']);
    $sexo = mysqli_real_escape_string($conection, $_POST['sexo']);
    $correo = mysqli_real_escape_string($conection, $_POST['correo']);
    $nombreUsuario = mysqli_real_escape_string($conection, $_POST['nombre-usuario']);
    $valorDocumento = mysqli_real_escape_string($conection, $_POST['valor-documento']);

    mysqli_begin_transaction($conection);

    try {
        $queryPersona = "
            UPDATE tb_personas_fisicas 
            SET nombres = '$nombres', apellidos = '$apellidos', fechaNacimiento = '$fechaNacimiento', sexo = '$sexo'
            WHERE idPersonaFisica = (SELECT persona_fisica_id FROM tb_usuarios WHERE idUsuario = '$idUsuario')
        ";
        $resultPersona = mysqli_query($conection, $queryPersona);

        $queryUsuario = "
            UPDATE tb_usuarios 
            SET emailUsuario = '$correo', nombreCuentaUsuario = '$nombreUsuario'
            WHERE idUsuario = '$idUsuario'
        ";
        $resultUsuario = mysqli_query($conection, $queryUsuario);

        if ($resultPersona && $resultUsuario) {
            mysqli_commit($conection);
            $_SESSION['mensaje'] = "La información ha sido actualizada correctamente.";
            $_SESSION['alert_type'] = "Éxito";
        } else {
            throw new Exception("Error al actualizar la información.");
        }
    } catch (Exception $e) {
        mysqli_rollback($conection);
        $_SESSION['mensaje'] = $e->getMessage();
        $_SESSION['alert_type'] = "Error";
    }

    mysqli_close($conection);

    header("Location: verPerfil.php");
    exit();
}
