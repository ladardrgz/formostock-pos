<?php
session_start();
include("../modelos/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $idSucursal = (int)$_POST['idSucursal'];
    $nombreSucursal = trim($_POST['nombreSucursal']);
    $personaJuridicaId = (int)$_POST['persona_juridica_id'];
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);

    // Validar que los campos no estén vacíos
    if ($idSucursal > 0 && !empty($nombreSucursal) && !empty($nombres) && !empty($apellidos)) {
        // Actualizar el nombre de la sucursal
        $querySucursal = "UPDATE tb_sucursal 
                          SET nombreSucursal = ?, persona_juridica_id = ?
                          WHERE idSucursal = ?";
        $stmtSucursal = $conection->prepare($querySucursal);
        $stmtSucursal->bind_param('sii', $nombreSucursal, $personaJuridicaId, $idSucursal);
        $stmtSucursal->execute();

        // Actualizar los nombres y apellidos de la persona física asociada a la persona jurídica
        $queryPersonaFisica = "UPDATE tb_personas_fisicas 
                               SET nombres = ?, apellidos = ?
                               WHERE idPersonaFisica = (SELECT persona_fisica_id 
                                                        FROM tb_personas_juridicas 
                                                        WHERE idPersonaJuridica = ?)";
        $stmtPersonaFisica = $conection->prepare($queryPersonaFisica);
        $stmtPersonaFisica->bind_param('ssi', $nombres, $apellidos, $personaJuridicaId);
        $stmtPersonaFisica->execute();

        $_SESSION['mensaje'] = 'Información de sucursal actualizado correctamente.';
    } else {
        $_SESSION['mensaje'] = 'Datos inválidos.';
    }

    // Redireccionar al inicio de sucursales
    header('Location: InicioSucursal.php');
    exit;
} else {
    $_SESSION['mensaje'] = 'Método de solicitud no permitido.';
    header('Location: InicioSucursal.php');
    exit;
}
