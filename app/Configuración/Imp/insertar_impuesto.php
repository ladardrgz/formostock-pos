<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario para agregar un impuesto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreImpuesto']) && isset($_POST['valorDetalleImpuesto'])) {
    $nombreImpuesto = mysqli_real_escape_string($conection, $_POST['nombreImpuesto']);
    $valorDetalleImpuesto = mysqli_real_escape_string($conection, $_POST['valorDetalleImpuesto']);

    // Comprobar si el nombre del impuesto ya existe en la base de datos
    $query_check = "SELECT COUNT(*) AS total FROM tb_tipo_impuestos WHERE nombreImpuesto = '$nombreImpuesto'";
    $result_check = mysqli_query($conection, $query_check);
    $data_check = mysqli_fetch_assoc($result_check);

    if ($data_check['total'] > 0) {
        // Si el impuesto ya existe, redirigir con un mensaje de error
        header('Location: tb_impuesto.php?message=error_name_exists');
        exit();
    }

    // Iniciar una transacción
    mysqli_begin_transaction($conection);

    try {
        // Insertar en la tabla tb_tipo_impuestos
        $query_tipo_impuesto = "INSERT INTO tb_tipo_impuestos (nombreImpuesto) VALUES ('$nombreImpuesto')";

        if (mysqli_query($conection, $query_tipo_impuesto)) {
            // Obtener el ID del impuesto recién insertado
            $idTipoImpuesto = mysqli_insert_id($conection);

            // Insertar el detalle del impuesto en la tabla tb_detalle_impuestos
            $query_detalle_impuesto = "INSERT INTO tb_detalle_impuestos (valorDetalleImpuesto, tipo_impuesto_id) 
            VALUES ('$valorDetalleImpuesto', '$idTipoImpuesto')";

            if (mysqli_query($conection, $query_detalle_impuesto)) {
                // Confirmar la transacción
                mysqli_commit($conection);
                // Redirigir con mensaje de éxito
                header('Location: tb_impuesto.php?message=added');
                exit();
            } else {
                throw new Exception("Error al agregar el detalle del impuesto: " . mysqli_error($conection));
            }
        } else {
            throw new Exception("Error al agregar el tipo de impuesto: " . mysqli_error($conection));
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
