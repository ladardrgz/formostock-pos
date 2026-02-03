<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreMarcaProducto'])) {
    $nombreMarcaProducto = mysqli_real_escape_string($conection, $_POST['nombreMarcaProducto']);

    // Insertar el nuevo registro en la tabla tb_marcas_productos
    $query = "INSERT INTO tb_marcas_productos (nombreMarcaProducto) VALUES ('$nombreMarcaProducto')";

    if (mysqli_query($conection, $query)) {
        // Si la inserción es exitosa, redirigir con mensaje de éxito
        header('Location: tb_marca.php?message=added');
        exit();
    } else {
        die("Error al agregar la marca: " . mysqli_error($conection));
    }
}

// Configuración de paginación y consultas
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consultar el número total de registros en la tabla tb_marcas_productos
$total_query = "SELECT COUNT(*) AS total FROM tb_marcas_productos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_records / $limit);

// Consultar los registros para mostrar en la página actual
$query = "SELECT idMarcaProducto, nombreMarcaProducto FROM tb_marcas_productos LIMIT $limit OFFSET $offset";
$result = mysqli_query($conection, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conection));
}

// Cerrar la conexión
mysqli_close($conection);
?>
