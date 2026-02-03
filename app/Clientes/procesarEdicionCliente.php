<?php
include_once '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idCliente = isset($_POST['idCliente']) ? (int)$_POST['idCliente'] : 0;
    $nombres = mysqli_real_escape_string($conection, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conection, $_POST['apellidos']);
    $fechaNacimiento = mysqli_real_escape_string($conection, $_POST['fechaNacimiento']);
    $sexo = mysqli_real_escape_string($conection, $_POST['sexo']);
    $estado_persona_id = mysqli_real_escape_string($conection, $_POST['estado_persona_id']);

    $updateQuery = "
        UPDATE tb_personas_fisicas
        SET nombres = '$nombres',
        apellidos = '$apellidos',
        fechaNacimiento = '$fechaNacimiento',
        sexo = '$sexo',
        estado_persona_id = '$estado_persona_id'
        WHERE idPersonaFisica = (SELECT persona_fisica_id FROM tb_clientes WHERE idCliente = $idCliente)
    ";

    if (mysqli_query($conection, $updateQuery)) {
        echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: 'La información del cliente ha sido actualizada correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            }).then(() => {
                window.location.href = 'dashboardClientes.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la información del cliente.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            });
        </script>";
    }
}
?>

