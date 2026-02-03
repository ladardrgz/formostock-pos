<?php 
include('../../modelos/conexion.php');

if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreTipoDoc'])) {
    $nombreTipoDoc = mysqli_real_escape_string($conection, $_POST['nombreTipoDoc']);

    // Verificación de duplicados
    $query_verificar = "SELECT COUNT(*) as count FROM tb_tipo_documentos WHERE nombreTipoDoc = '$nombreTipoDoc'";
    $resultado_verificar = mysqli_query($conection, $query_verificar);
    $row = mysqli_fetch_assoc($resultado_verificar);

    if ($row['count'] > 0) {
        header('Location: frm_tb_documentos.php?error=nombre_en_uso');
        exit();
    } else {
        // Inicio de la transacción
        mysqli_begin_transaction($conection);

        try {
            $query_tipo_documento = "INSERT INTO tb_tipo_documentos (nombreTipoDoc) VALUES ('$nombreTipoDoc')";

            if (mysqli_query($conection, $query_tipo_documento)) {
                mysqli_commit($conection);
                header('Location: frm_tb_documentos.php?message=added');
                exit();
            } else {
                throw new Exception("Error al agregar el tipo de documento: " . mysqli_error($conection));
            }
        } catch (Exception $e) {
            mysqli_rollback($conection);
            die($e->getMessage());
        }
    }
}

?>
