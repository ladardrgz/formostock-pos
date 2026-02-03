<?php
session_start();
include '../modelos/conexion.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    $query = "SELECT * FROM tb_usuarios WHERE emailUsuario = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode($result->num_rows > 0);
}
?>
