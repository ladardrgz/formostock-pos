<?php
include_once '../modelos/conexion.php';

header('Content-Type: application/json');

if (!isset($_POST['idCaja']) || empty($_POST['idCaja'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibió el código de la caja.'
    ]);
    exit;
}
$idCaja = (int) $_POST['idCaja'];

try {
    $query_verificar_saldo = "
        SELECT saldoActualCaja 
        FROM tb_caja 
        WHERE idCaja = ?
    ";
    $stmt = mysqli_prepare($conection, $query_verificar_saldo);
    mysqli_stmt_bind_param($stmt, 'i', $idCaja);
    mysqli_stmt_execute($stmt);
    $resultado_saldo = mysqli_stmt_get_result($stmt);

    if (!$resultado_saldo || mysqli_num_rows($resultado_saldo) == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'La caja no existe o ya fue eliminada.'
        ]);
        exit;
    }

    $saldo = mysqli_fetch_assoc($resultado_saldo)['saldoActualCaja'];

    if ($saldo > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'La caja no puede ser eliminada porque contiene transacciones asociadas. Eliminarla podría afectar la consistencia y la integridad de los datos del sistema.'
        ]);
        exit;
    }

    $query_eliminar = "
        DELETE FROM tb_caja 
        WHERE idCaja = ?
    ";
    $stmt_eliminar = mysqli_prepare($conection, $query_eliminar);
    mysqli_stmt_bind_param($stmt_eliminar, 'i', $idCaja);
    $resultado_eliminar = mysqli_stmt_execute($stmt_eliminar);

    if ($resultado_eliminar) {
        echo json_encode([
            'success' => true,
            'message' => 'Caja eliminada correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Hubo un error al intentar eliminar la caja.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>
