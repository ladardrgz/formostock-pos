<?php
session_start();
require_once '../modelos/conexion.php';

if (!$conection) {
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . mysqli_connect_error()]);
    exit;
}

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(["status" => "error", "message" => "No se ha iniciado sesión o no hay un usuario identificado."]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

// Recoger datos del formulario
$nombreCuentaUsuario = mysqli_real_escape_string($conection, $_POST['nombreCuentaUsuario']);
$emailUsuario = mysqli_real_escape_string($conection, $_POST['emailUsuario']);
$nombres = mysqli_real_escape_string($conection, $_POST['nombres']);
$apellidos = mysqli_real_escape_string($conection, $_POST['apellidos']);
$fechaNacimiento = mysqli_real_escape_string($conection, $_POST['fechaNacimiento']);
$sexo = mysqli_real_escape_string($conection, $_POST['sexo']);

// Actualizar datos del perfil
$query = "UPDATE tb_personas_fisicas pf
          INNER JOIN tb_usuarios u ON u.persona_fisica_id = pf.idPersonaFisica
          SET u.nombreCuentaUsuario = '$nombreCuentaUsuario', 
              u.emailUsuario = '$emailUsuario',
              pf.nombres = '$nombres', 
              pf.apellidos = '$apellidos', 
              pf.fechaNacimiento = '$fechaNacimiento',
              pf.sexo = '$sexo'
          WHERE u.idUsuario = '$idUsuario'";

$update_query = mysqli_query($conection, $query);

if ($update_query) {
    echo json_encode(["status" => "success", "message" => "Perfil actualizado exitosamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al actualizar el perfil."]);
}

mysqli_close($conection);
?>
