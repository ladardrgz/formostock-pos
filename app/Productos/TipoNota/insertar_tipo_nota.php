<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario para agregar un tipo de nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipoNota'])) {
    $tipoNota = mysqli_real_escape_string($conection, $_POST['tipoNota']);

    // Iniciar una transacción
    mysqli_begin_transaction($conection);

    try {
        // Insertar en la tabla tb_tipos_notas
        $query_tipo_nota = "INSERT INTO tb_tipos_notas (tipoNota) VALUES ('$tipoNota')";

        if (mysqli_query($conection, $query_tipo_nota)) {
            // Confirmar la transacción
            mysqli_commit($conection);
            // Redirigir con mensaje de éxito
            header('Location: tb_tipo_nota.php?message=added');
            exit();
        } else {
            throw new Exception("Error al agregar el tipo de nota: " . mysqli_error($conection));
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
