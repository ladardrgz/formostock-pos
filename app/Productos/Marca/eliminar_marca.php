<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar si se ha enviado el ID del estado a eliminar
if (isset($_GET['id'])) {
    $idMarcaProducto = (int)$_GET['id'];

    // Verificar la conexión
    if (!$conection) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Consulta para eliminar el estado lógico
    $query = "DELETE FROM tb_marcas_productos WHERE idMarcaProducto = $idMarcaProducto";

    if (mysqli_query($conection, $query)) {
        // Si la eliminación es exitosa, redirigir
        header('Location: tb_marca.php?message=deleted');
        exit();
    } else {
        die("Error al eliminar la marca: " . mysqli_error($conection));
    }
} else {
    // Redirigir si no se proporciona un ID
    header('Location: tb_marca.php');
    exit();
}
?>
