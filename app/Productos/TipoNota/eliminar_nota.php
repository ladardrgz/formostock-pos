<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar si se ha enviado el ID de la nota a eliminar
if (isset($_GET['id'])) {
    $idTipoNota = (int)$_GET['id'];

    // Verificar la conexión
    if (!$conection) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Consulta para eliminar la nota
    $query = "DELETE FROM tb_tipos_notas WHERE idTipoNota = $idTipoNota";

    if (mysqli_query($conection, $query)) {
        // Si la eliminación es exitosa, redirigir
        header('Location: tb_tipo_nota.php?message=deleted');
        exit();
    } else {
        die("Error al eliminar la nota: " . mysqli_error($conection));
    }
} else {
    // Redirigir si no se proporciona un ID
    header('Location: tb_tipo_nota.php');
    exit();
}
?>
