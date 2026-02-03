<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario para agregar un nuevo período
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombrePeriodo']) && isset($_POST['fechaInicioPeriodo']) && isset($_POST['fechaFinPeriodo']) && isset($_POST['añoPeriodo']) && isset($_POST['estado_periodo_id'])) {
    
    // Obtener los valores del formulario
    $nombrePeriodo = mysqli_real_escape_string($conection, $_POST['nombrePeriodo']);
    $fechaInicioPeriodo = mysqli_real_escape_string($conection, $_POST['fechaInicioPeriodo']);
    $fechaFinPeriodo = mysqli_real_escape_string($conection, $_POST['fechaFinPeriodo']);
    $añoPeriodo = (int) $_POST['añoPeriodo'];
    $estadoPeriodoId = (int) $_POST['estado_periodo_id'];

    // Verificar si el nombre del período ya existe
    $queryCheck = "SELECT COUNT(*) AS total FROM tb_periodos WHERE nombrePeriodo = '$nombrePeriodo'";
    $resultCheck = mysqli_query($conection, $queryCheck);
    if ($resultCheck) {
        $data = mysqli_fetch_assoc($resultCheck);
        
        if ($data['total'] > 0) {
            // Si el nombre del período ya existe, redirigir con un mensaje de error
            header('Location: tb_periodo.php?message=error_name_exists');
            exit();
        }
    } else {
        // Error al ejecutar la consulta de verificación
        die("Error al verificar el nombre del período: " . mysqli_error($conection));
    }

    // Iniciar una transacción
    mysqli_begin_transaction($conection);

    try {
        // Consulta para insertar en la tabla tb_periodos
        $query_periodo = "INSERT INTO tb_periodos (nombrePeriodo, fechaInicioPeriodo, fechaFinPeriodo, añoPeriodo, estado_periodo_id) 
                          VALUES ('$nombrePeriodo', '$fechaInicioPeriodo', '$fechaFinPeriodo', $añoPeriodo, $estadoPeriodoId)";

        if (mysqli_query($conection, $query_periodo)) {
            // Confirmar la transacción
            mysqli_commit($conection);
            // Redirigir con mensaje de éxito
            header('Location: tb_periodo.php?message=added');
            exit();
        } else {
            throw new Exception("Error al agregar el período: " . mysqli_error($conection));
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
