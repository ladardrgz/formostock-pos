<?php
session_start(); // Iniciar la sesión para almacenar mensajes

include_once 'Database.php';
include_once 'PersonaFisica.php';
include_once 'PersonaJuridica.php';
include_once 'Sucursal.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancia de PersonaFisica y asignar los valores del formulario
$personaFisica = new PersonaFisica($db);
$personaFisica->nombres = $_POST['nombres'];
$personaFisica->apellidos = $_POST['apellidos'];
$personaFisica->fechaNacimiento = $_POST['fechaNacimiento'];
$personaFisica->sexo = $_POST['sexo'];

// Insertar detalle de documento
$tipoDocumentoId = $_POST['tipo_documento_id'];
$valorDocumento = $_POST['valorDocumento'];
$personaFisica->detalle_documento_id = $personaFisica->insertarDetalleDocumento($tipoDocumentoId, $valorDocumento);

// Insertar detalle de contacto
$tipoContactoId = $_POST['tipo_contacto_id'];
$valorContacto = $_POST['valorContacto'];
$personaFisica->detalle_contacto_id = $personaFisica->insertarDetalleContacto($tipoContactoId, $valorContacto);

// Insertar persona física
if ($personaFisica->create()) {
    // Crear instancia de PersonaJuridica y asignar valores
    $personaJuridica = new PersonaJuridica($db);
    $personaJuridica->razonSocial = $_POST['razonSocial'];
    $personaJuridica->persona_fisica_id = $personaFisica->id;

    // Insertar persona jurídica
    if ($personaJuridica->create()) {
        // Crear instancia de Sucursal y asignar valores
        $sucursal = new Sucursal($db);
        $sucursal->nombreSucursal = $_POST['nombreSucursal'];
        $sucursal->persona_juridica_id = $personaJuridica->id;

        // Insertar sucursal
        if ($sucursal->create()) {
            $_SESSION['mensaje'] = "Sucursal registrada exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al registrar la sucursal.";
        }
    } else {
        $_SESSION['mensaje'] = "Error al registrar la persona jurídica.";
    }
} else {
    $_SESSION['mensaje'] = "Error al registrar la persona física.";
}

// Redirigir a la vista de InicioSucursal.php
header("Location: InicioSucursal.php");
exit;
?>
