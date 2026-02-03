<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreCategoriaProducto'])) {
    $nombreCategoriaProducto = mysqli_real_escape_string($conection, $_POST['nombreCategoriaProducto']);

    // Insertar el nuevo estado lógico en la base de datos
    $query = "INSERT INTO tb_categorias_productos (nombreCategoriaProducto) VALUES ('$nombreCategoriaProducto')";

    if (mysqli_query($conection, $query)) {
        // Si la inserción es exitosa, redirigir con el mensaje 'added'
        header('Location: tb_categoria.php?message=added');
        exit();
    } else {
        // Si ocurre un error, redirigir con el mensaje 'error'
        header('Location: tb_categoria.php?message=error');
        exit();
    }
}

// Cerrar la conexión
mysqli_close($conection);
?>
