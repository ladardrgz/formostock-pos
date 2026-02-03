<?php
include('../../modelos/conexion.php');

// Verificar si se ha recibido un ID para eliminar
if (isset($_GET['id'])) {
    $idLocalidad = (int)$_GET['id'];

    // Preparar la consulta para eliminar la localidad
    $query = "DELETE FROM tb_localidades WHERE idLocalidad = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param('i', $idLocalidad);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir con un mensaje de éxito
        header('Location: tb_localidades.php?message=deleted');
    } else {
        // Redirigir con un mensaje de error
        header('Location: tb_localidades.php?message=error');
    }

    $stmt->close();
}

$conection->close();
?>
