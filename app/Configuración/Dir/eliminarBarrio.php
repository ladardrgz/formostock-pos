<?php
include('../../modelos/conexion.php');

// Verificar si se ha recibido un ID para eliminar
if (isset($_GET['id'])) {
    $idBarrio = (int)$_GET['id'];

    // Comprobar si el barrio está en uso en la tabla `tb_domicilios`
    $consultaComprobacionDomicilios = "
        SELECT COUNT(*) AS cantidad
        FROM tb_domicilios
        WHERE barrio_id = ?
    ";
    $stmtComprobacionDomicilios = $conection->prepare($consultaComprobacionDomicilios);
    $stmtComprobacionDomicilios->bind_param('i', $idBarrio);
    $stmtComprobacionDomicilios->execute();
    $resultadoComprobacionDomicilios = $stmtComprobacionDomicilios->get_result();
    $filaDomicilios = $resultadoComprobacionDomicilios->fetch_assoc();

    // Comprobar si el barrio está en uso en la tabla `tb_domicilios_personas`
    $consultaComprobacionDomiciliosPersonas = "
        SELECT COUNT(*) AS cantidad
        FROM tb_domicilios_personas dp
        JOIN tb_domicilios d ON dp.domicilio_id = d.idDomicilio
        WHERE d.barrio_id = ?
    ";
    $stmtComprobacionDomiciliosPersonas = $conection->prepare($consultaComprobacionDomiciliosPersonas);
    $stmtComprobacionDomiciliosPersonas->bind_param('i', $idBarrio);
    $stmtComprobacionDomiciliosPersonas->execute();
    $resultadoComprobacionDomiciliosPersonas = $stmtComprobacionDomiciliosPersonas->get_result();
    $filaDomiciliosPersonas = $resultadoComprobacionDomiciliosPersonas->fetch_assoc();

    // Si el barrio está en uso en alguna de las tablas
    if ($filaDomicilios['cantidad'] > 0 || $filaDomiciliosPersonas['cantidad'] > 0) {
        // Si el barrio está en uso, redirigir con un mensaje de error
        header('Location: tb_barrios.php?page=1&message=en_uso');
    } else {
        // Preparar la consulta para eliminar el barrio
        $consultaEliminacion = "DELETE FROM tb_barrios WHERE idBarrio = ?";
        $stmtEliminacion = $conection->prepare($consultaEliminacion);
        $stmtEliminacion->bind_param('i', $idBarrio);

        // Ejecutar la consulta
        if ($stmtEliminacion->execute()) {
            header('Location: tb_barrios.php?page=1&message=eliminado');
        } else {
            header('Location: tb_barrios.php?page=1&message=error');
        }

        $stmtEliminacion->close();
    }

    $stmtComprobacionDomicilios->close();
    $stmtComprobacionDomiciliosPersonas->close();
}

$conection->close();
?>
