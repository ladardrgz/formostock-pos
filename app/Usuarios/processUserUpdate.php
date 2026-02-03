<?php
include '../modelos/conexion.php';

$idUsuario = $_POST['idUsuario'];
$nombreCuentaUsuario = $_POST['nombreCuentaUsuario'];
$emailUsuario = $_POST['emailUsuario'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$sexo = $_POST['sexo'];

// Actualizar información en tb_usuarios
$query_usuarios = "UPDATE tb_usuarios SET nombreCuentaUsuario='$nombreCuentaUsuario', emailUsuario='$emailUsuario' WHERE idUsuario=$idUsuario";
$result_usuarios = mysqli_query($conection, $query_usuarios);

// Obtener idPersonaFisica
$query_persona = "SELECT persona_fisica_id FROM tb_usuarios WHERE idUsuario = $idUsuario";
$result_persona = mysqli_query($conection, $query_persona);
$row_persona = mysqli_fetch_assoc($result_persona);
$idPersonaFisica = $row_persona['persona_fisica_id'];

// Actualizar información en tb_personas_fisicas
$query_persona_fisica = "UPDATE tb_personas_fisicas SET nombres='$nombres', apellidos='$apellidos', fechaNacimiento='$fechaNacimiento', sexo='$sexo' WHERE idPersonaFisica=$idPersonaFisica";
$result_persona_fisica = mysqli_query($conection, $query_persona_fisica);

if($result_usuarios && $result_persona_fisica){
    echo "Información actualizada correctamente.";
} else {
    echo "Error al actualizar la información.";
}
?>
