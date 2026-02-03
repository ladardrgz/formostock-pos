<?php 
// Incluir el archivo de conexión
include('../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario para agregar un tipo de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreTipoContacto'])) {
    $nombreTipoContacto = mysqli_real_escape_string($conection, $_POST['nombreTipoContacto']);

    // Iniciar una transacción
    mysqli_begin_transaction($conection);

    try {
        // Insertar en la tabla tb_tipo_contacto
        $query_tipo_contacto = "INSERT INTO tb_tipo_contacto (nombreTipoContacto) VALUES ('$nombreTipoContacto')";

        if (mysqli_query($conection, $query_tipo_contacto)) {
            // Confirmar la transacción
            mysqli_commit($conection);
            // Redirigir con mensaje de éxito
            header('Location: tb_tipo_contacto.php?message=added');
            exit();
        } else {
            throw new Exception("Error al agregar el tipo de contacto: " . mysqli_error($conection));
        }
    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback
        mysqli_rollback($conection);
        die($e->getMessage());
    }
}

// Cerrar la conexión
mysqli_close($conection);
?>
