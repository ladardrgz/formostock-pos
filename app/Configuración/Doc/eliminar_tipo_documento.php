<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar si se ha enviado el ID del tipo de documento a eliminar
if (isset($_GET['id'])) {
    $idTipoDocumento = (int)$_GET['id'];

    // Verificar la conexión
    if (!$conection) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Comprobar si el tipo de documento está en uso en la tabla `tb_detalle_documento`
    $query_verificar_uso = "SELECT COUNT(*) as count FROM tb_detalle_documento WHERE tipo_documento_id = $idTipoDocumento";
    $resultado_verificar_uso = mysqli_query($conection, $query_verificar_uso);
    $row = mysqli_fetch_assoc($resultado_verificar_uso);

    if ($row['count'] > 0) {
        // Si el tipo de documento está en uso, redirigir con mensaje de advertencia
        header('Location: tb_tipo_documento.php?message=en_uso');
        exit();
    }

    // Si no está en uso, proceder con la eliminación
    $query = "DELETE FROM tb_tipo_documentos WHERE idTipoDocumento = $idTipoDocumento";
    if (mysqli_query($conection, $query)) {
        // Eliminación exitosa
        header('Location: tb_tipo_documento.php?message=eliminado');
        exit();
    } else {
        // Error en la consulta de eliminación
        header('Location: tb_tipo_documento.php?message=error');
        exit();
    }
} else {
    // Redirigir a la página de listado si no se proporciona un ID
    header('Location: tb_tipo_documento.php');
    exit();
}
