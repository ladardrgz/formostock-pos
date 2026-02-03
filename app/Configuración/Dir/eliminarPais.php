<?php
include('../../modelos/conexion.php');

// Verificar si se ha recibido un ID para eliminar
if (isset($_GET['id'])) {
    $idPais = (int)$_GET['id'];

    // Verificar si el país está asociado a alguna provincia en la tabla tb_provincias
    $checkQueryProvincias = "
        SELECT COUNT(*) AS count 
        FROM tb_provincias 
        WHERE pais_id = ?
    ";

    // Preparar y ejecutar la consulta para verificar asociaciones con provincias
    if ($stmt = $conection->prepare($checkQueryProvincias)) {
        $stmt->bind_param('i', $idPais);
        $stmt->execute();
        $stmt->bind_result($countProvincias);
        $stmt->fetch();
        $stmt->close();

        // Si el país está en uso en provincias
        if ($countProvincias > 0) {
            // Redirigir con un mensaje indicando que está en uso
            header('Location: tb_paises.php?page=1&message=en_uso');
            exit;
        }
    } else {
        // Error en la consulta de verificación
        header('Location: tb_paises.php?page=1&message=error');
        exit;
    }

    // Si no está en uso, intentar eliminar el país
    $deleteQuery = "DELETE FROM tb_paises WHERE idPais = ?";
    if ($stmt = $conection->prepare($deleteQuery)) {
        $stmt->bind_param('i', $idPais);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir con un mensaje de éxito
            header('Location: tb_paises.php?page=1&message=eliminado');
        } else {
            // Redirigir con un mensaje de error
            header('Location: tb_paises.php?page=1&message=error');
        }

        $stmt->close();
    } else {
        // Error en la preparación de la consulta de eliminación
        header('Location: tb_paises.php?page=1&message=error');
    }
} else {
    // Si no se recibe un ID, redirigir con un mensaje de error
    header('Location: tb_paises.php?page=1&message=error');
}

$conection->close();
?>
