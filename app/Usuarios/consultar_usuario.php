<?php
session_start();
include '../modelos/conexion.php';

if (isset($_GET['nombreUsuario'])) {
    $nombreUsuario = $_GET['nombreUsuario'];
    
    $query = "SELECT * FROM tb_usuarios WHERE nombreCuentaUsuario = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode($result->num_rows > 0);
}
?>
