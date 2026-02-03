<?php
// Definir la carpeta donde se almacenarán las imágenes
$carpetaImagenes = 'imagenes/';

// Crear la carpeta si no existe
if (!is_dir($carpetaImagenes)) {
    mkdir($carpetaImagenes, 0777, true);
}

// Verifica si se ha enviado un archivo
if (isset($_FILES['imagenProducto']) && $_FILES['imagenProducto']['error'] == 0) {
    // Definir el nombre y la ruta del archivo
    $nombreImagen = uniqid() . '-' . basename($_FILES['imagenProducto']['name']); // Evita conflictos de nombres
    $rutaImagen = $carpetaImagenes . $nombreImagen;
    
    // Verifica si el archivo es una imagen válida
    $tipoImagen = $_FILES['imagenProducto']['type'];
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    
    if (in_array($tipoImagen, $tiposPermitidos)) {
        // Mueve el archivo a la carpeta y verifica si la operación fue exitosa
        if (move_uploaded_file($_FILES['imagenProducto']['tmp_name'], $rutaImagen)) {
            // Construir la URL accesible de la imagen
            $urlImagen = $rutaImagen; // Cambia esto si necesitas un formato de URL diferente
            echo json_encode([
                'success' => true,
                'message' => 'Imagen subida exitosamente.',
                'url' => $urlImagen // Retorna la URL de la imagen
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al mover la imagen al directorio de destino.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de archivo no permitido.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se ha enviado ningún archivo o hubo un error en la subida.'
    ]);
}
?>
