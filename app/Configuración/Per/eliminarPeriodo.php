<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar si se ha enviado el ID del período a eliminar
if (isset($_GET['id'])) {
    $idPeriodo = (int)$_GET['id']; // Convertir el ID a entero para mayor seguridad

    // Verificar la conexión
    if (!$conection) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Verificar si el período está en uso en la tabla `tb_periodo_productos`
    $queryCheck = "SELECT COUNT(*) AS total FROM tb_periodo_productos WHERE periodo_id = $idPeriodo";
    $resultCheck = mysqli_query($conection, $queryCheck);

    if ($resultCheck) {
        $data = mysqli_fetch_assoc($resultCheck);

        if ($data['total'] > 0) {
            // Si el período está en uso, redirigir con mensaje de error
            header('Location: tb_periodo.php?message=error_in_use');
            exit();
        }
    } else {
        // Error al ejecutar la consulta de verificación
        die("Error al verificar el uso del período: " . mysqli_error($conection));
    }

    // Si el período no está en uso, proceder a eliminarlo
    $queryDelete = "DELETE FROM tb_periodos WHERE idPeriodo = $idPeriodo";

    if (mysqli_query($conection, $queryDelete)) {
        // Si la eliminación es exitosa, redirigir con mensaje de éxito
        header('Location: tb_periodo.php?message=deleted');
        exit();
    } else {
        // Mostrar mensaje de error si no se pudo eliminar
        die("Error al eliminar el período: " . mysqli_error($conection));
    }
} else {
    // Redirigir si no se proporciona un ID válido
    header('Location: tb_periodo.php');
    exit();
}
?>
