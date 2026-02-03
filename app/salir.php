<?php
require_once 'modelos/conexion.php';

session_start();

if (isset($_SESSION['idUsuario'])) {
    $idUsuario = $_SESSION['idUsuario'];

    if ($conection) {
        $sql = "UPDATE tb_sesiones SET activo = 0 WHERE idUsuario = ? AND activo = 1";
        $stmt = mysqli_prepare($conection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $idUsuario);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Error en la conexión a la base de datos.";
        exit;
    }
    mysqli_close($conection);
}

session_destroy();

header('Location: ../');
exit;
?>
