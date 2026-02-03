<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../paginaPrincipal.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../modelos/conexion.php";

    $idUsuario = $_SESSION['idUsuario'];
    $password_actual = $_POST['password_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Consultar la contraseña actual del usuario desde la base de datos
    $query = mysqli_query($conection, "SELECT contraseñaUsuario FROM tb_usuarios WHERE idUsuario = '$idUsuario'");
    $result = mysqli_fetch_assoc($query);
    $contraseñaUsuario = $result['contraseñaUsuario'];

    // Inicializar mensaje de respuesta
    $response = [];

    // Verificar si la contraseña actual ingresada coincide con la contraseña almacenada en la base de datos
    if (md5($password_actual) == $contraseñaUsuario) {
        // Verificar si la nueva contraseña es diferente de la actual
        if (md5($nueva_contrasena) != $contraseñaUsuario) {
            // Verificar si la nueva contraseña y la confirmación coinciden
            if ($nueva_contrasena == $confirmar_contrasena) {
                // Actualizar la contraseña en la base de datos
                $contraseñaUsuarioEncriptada = md5($nueva_contrasena);
                $update_query = mysqli_query($conection, "UPDATE tb_usuarios SET contraseñaUsuario = '$contraseñaUsuarioEncriptada' WHERE idUsuario = '$idUsuario'");
                if ($update_query) {
                    $response = [
                        "status" => "success",
                        "message" => "¡Contraseña actualizada exitosamente!",
                        "redirect" => "reset_user_password.php"
                    ];
                } else {
                    $response = [
                        "status" => "error",
                        "message" => "Error al actualizar la contraseña."
                    ];
                }
            } else {
                $response = [
                    "status" => "error",
                    "message" => "La nueva contraseña y la confirmación de la nueva contraseña no coinciden."
                ];
            }
        } else {
            $response = [
                "status" => "error",
                "message" => "La nueva contraseña no puede ser igual a la actual ni a la anterior."
            ];
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "La contraseña actual ingresada no es correcta."
        ];
    }

    // Retornar la respuesta como JSON
    echo json_encode($response);
}
?>
