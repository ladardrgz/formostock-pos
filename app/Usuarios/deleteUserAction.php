<?php
include_once '../modelos/sesion.php';
include_once '../modelos/conexion.php';

$response = ['success' => false, 'message' => ''];

// Verificar si la sesión está activa
if (!SesionUsuario::sesionActiva()) {
    $response['message'] = 'Sesión no válida. Por favor, inicie sesión nuevamente.';
    echo json_encode($response);
    exit;
}

// Obtener el ID del usuario logueado desde la sesión
$loggedUserId = $_SESSION['idUsuario'];

// Obtener el ID del usuario a eliminar desde la solicitud POST
$idUsuario = isset($_POST['idUsuario']) ? (int)$_POST['idUsuario'] : 0;

if ($idUsuario === 0) {
    $response['message'] = 'ID de usuario inválido.';
    echo json_encode($response);
    exit;
}

// Verificar que el usuario no se esté autoeliminando
if ($loggedUserId === $idUsuario) {
    $response['message'] = 'No puedes eliminar tu propia cuenta mientras estés logueado.';
    echo json_encode($response);
    exit;
}

// Comenzar una transacción para asegurar la consistencia de la base de datos
mysqli_begin_transaction($conection);

try {
    // Preparar la consulta para eliminar el usuario
    $query_delete = "DELETE FROM tb_usuarios WHERE idUsuario = ?";
    $stmt = mysqli_prepare($conection, $query_delete);
    mysqli_stmt_bind_param($stmt, 'i', $idUsuario);

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_stmt_execute($stmt)) {
        // Confirmar la transacción si la eliminación fue exitosa
        mysqli_commit($conection);
        $response['success'] = true;
        $response['message'] = 'Usuario eliminado exitosamente.';
    } else {
        // Revertir la transacción si ocurrió un error
        mysqli_rollback($conection);
        $response['message'] = 'Error al eliminar el usuario.';
    }

    // Cerrar la declaración preparada
    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    // Revertir la transacción en caso de excepción
    mysqli_rollback($conection);
    $response['message'] = 'Ocurrió un error al procesar la solicitud.';
}

// Cerrar la conexión a la base de datos
mysqli_close($conection);

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
