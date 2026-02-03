<?php
session_start();
include '../modelos/conexion.php';

if (isset($_GET['valorDetalleContacto'])) {
    $valorDetalleContacto = $_GET['valorDetalleContacto'];

    // Consulta para verificar si el valor de detalle de contacto existe en la tabla tb_detalle_contacto
    $query = "SELECT * FROM tb_detalle_contacto WHERE valorDetalleContacto = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("s", $valorDetalleContacto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Devolver true si existe, false si no
    echo json_encode($result->num_rows > 0);
}
?>
