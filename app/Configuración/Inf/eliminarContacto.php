<?php
// Incluir la conexión a la base de datos
include('../../modelos/conexion.php');

// Verificar si se ha recibido un ID para eliminar
if (isset($_GET['id'])) {
    $idTipoContacto = (int)$_GET['id'];

    // Comprobar si el tipo de contacto está en uso en la tabla `tb_detalle_contacto`
    $consultaComprobacionUso = "
        SELECT COUNT(*) AS cantidad
        FROM tb_detalle_contacto
        WHERE tipo_contacto_id = ?
    ";

    $stmtComprobacionUso = $conection->prepare($consultaComprobacionUso);
    $stmtComprobacionUso->bind_param('i', $idTipoContacto);
    $stmtComprobacionUso->execute();
    $resultadoComprobacionUso = $stmtComprobacionUso->get_result();
    $filaUso = $resultadoComprobacionUso->fetch_assoc();

    if ($filaUso['cantidad'] > 0) {
        header('Location: tb_tipoContacto.php?message=in_use');
    } else {
        $query = "DELETE FROM tb_tipo_contacto WHERE idTipoContacto = ?";
        $stmt = $conection->prepare($query);
        $stmt->bind_param('i', $idTipoContacto);

        if ($stmt->execute()) {
            header('Location: tb_tipoContacto.php?message=deleted');
        } else {
            header('Location: tb_tipoContacto.php?message=error');
        }

        $stmt->close();
    }

    $stmtComprobacionUso->close();
}

$conection->close();
?>
