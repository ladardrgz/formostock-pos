<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar si se ha enviado el ID de la categoría a eliminar
if (isset($_GET['id'])) {
    $idCategoriaProducto = (int)$_GET['id'];

    // Verificar la conexión
    if (!$conection) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Consulta para eliminar la categoría
    $query = "DELETE FROM tb_categorias_productos WHERE idCategoriaProducto = $idCategoriaProducto";

    if (mysqli_query($conection, $query)) {
        // Si la eliminación es exitosa, redirigir con el mensaje "deleted"
        header('Location: tb_categoria.php?message=deleted');
        exit();
    } else {
        // Si ocurre un error, redirigir con el mensaje "error"
        header('Location: tb_categoria.php?message=error');
        exit();
    }
} else {
    // Redirigir si no se proporciona un ID
    header('Location: tb_categoria.php');
}
?>

