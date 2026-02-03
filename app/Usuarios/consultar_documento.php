<?php
session_start();
include '../modelos/conexion.php';

if (isset($_GET['valorDocumento'])) {
    $valorDocumento = $_GET['valorDocumento'];

    // Consulta para verificar si el valor del documento existe en la tabla tb_detalle_documento
    $query = "SELECT * FROM tb_detalle_documento WHERE valorDocumento = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("s", $valorDocumento);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Devolver true si existe, false si no
    echo json_encode($result->num_rows > 0);
}
?>
