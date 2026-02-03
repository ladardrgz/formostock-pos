<?php
session_start(); // Asegúrate de iniciar la sesión
include '../modelos/conexion.php';
include 'fetch_proveedores.php'; 

// Definir la carpeta donde se almacenarán las imágenes
$carpetaImagenes = 'imagenes/';

// Crear la carpeta si no existe
if (!is_dir($carpetaImagenes)) {
    mkdir($carpetaImagenes, 0777, true);
}

// Manejo de la imagen
$imagenProducto = '';
if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['imagenProducto']['type'], $allowed_types)) {
        // Cambiar el nombre de la imagen para evitar conflictos
        $nombreImagen = uniqid() . '-' . basename($_FILES['imagenProducto']['name']);
        $imagenProducto = $carpetaImagenes . $nombreImagen;
        if (!move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $imagenProducto)) {
            $_SESSION['mensaje'] = 'Error al subir la imagen.';
            header('Location: nuevoProducto.php'); // Redirigir
            exit;
        }
    } else {
        $_SESSION['mensaje'] = 'Tipo de archivo no permitido.';
        header('Location: nuevoProducto.php'); // Redirigir
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapar los datos para prevenir inyecciones SQL
    $codigoBarrasProducto = mysqli_real_escape_string($conection, $_POST['codigoBarrasProducto']);
    $numeroDeSerieProducto = mysqli_real_escape_string($conection, $_POST['numeroDeSerieProducto']);
    $descripcionProducto = mysqli_real_escape_string($conection, $_POST['descripcionProducto']);
    $precioProducto = mysqli_real_escape_string($conection, $_POST['precioProducto']);
    $stockMinProducto = mysqli_real_escape_string($conection, $_POST['stockMinProducto']);
    $stockMaxProducto = mysqli_real_escape_string($conection, $_POST['stockMaxProducto']);
    $garantiaProducto = mysqli_real_escape_string($conection, $_POST['garantiaProducto']);
    $impuesto_id = mysqli_real_escape_string($conection, $_POST['impuesto_id']);
    $categoria_id = mysqli_real_escape_string($conection, $_POST['categoria_id']);
    $marca_id = mysqli_real_escape_string($conection, $_POST['marca_id']);
    $estado_producto_id = mysqli_real_escape_string($conection, $_POST['estado_producto_id']);
    $proveedor_id = mysqli_real_escape_string($conection, $_POST['proveedor_id']);

    // Uso de una sentencia preparada para insertar el producto
    $sql = $conection->prepare(
        "INSERT INTO tb_productos (
            codigoBarrasProducto, 
            numeroDeSerieProducto, 
            descripcionProducto, 
            precioProducto, 
            stockMinProducto, 
            stockMaxProducto, 
            garantiaProducto, 
            imagenProducto, 
            impuesto_id, 
            categoria_id, 
            marca_id, 
            estado_producto_id,
            proveedor_id
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $sql->bind_param(
        "sssdiisssiiii", 
        $codigoBarrasProducto, 
        $numeroDeSerieProducto, 
        $descripcionProducto, 
        $precioProducto, 
        $stockMinProducto, 
        $stockMaxProducto, 
        $garantiaProducto, 
        $imagenProducto, 
        $impuesto_id, 
        $categoria_id, 
        $marca_id, 
        $estado_producto_id,
        $proveedor_id
    );

    // Ejecutar la consulta
    if ($sql->execute()) {
        // Obtener el ID del producto recién insertado
        $idProducto = $conection->insert_id; // LAST_INSERT_ID()
        
        $_SESSION['mensaje'] = 'Producto registrado exitosamente.'; // Almacenar mensaje en la sesión
        
        // Redirigir a la página para ingresar stock por sucursal, pasando el idProducto
        header("Location: RegistrarStock.php?idProducto=$idProducto"); // Redirigir
        exit;
    } else {
        $_SESSION['mensaje'] = 'Error al registrar el producto: ' . $conection->error;
        header('Location: nuevoProducto.php'); // Redirigir
        exit;
    }

}
?>