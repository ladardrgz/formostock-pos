<?php
session_start();
include_once '../modelos/conexion.php';

$sucursalId = $_SESSION['sucursal_id'];
$facturaId = (int)$_POST['factura_id'];
$motivoDevolucion = $_POST['motivo_devolucion'];
$condicionesEntrega = $_POST['condiciones_entrega'];
$tipoDevolucion = $_POST['tipo_devolucion'];
$tipoNotaId = (int)$_POST['tipo_nota_id'];
$productosSeleccionados = $_POST['productos_seleccionados'];
$cantidadesDevolucion = $_POST['cantidad_devolucion'];
$montoTotalFactura = 0;
$errorOcurrido = false;
$mensaje = '';

$queryVerificarDevolucion = "SELECT COUNT(*) AS totalDevoluciones FROM tb_devoluciones WHERE factura_cabecera_id = ?";
$stmtVerificarDevolucion = mysqli_prepare($conection, $queryVerificarDevolucion);
mysqli_stmt_bind_param($stmtVerificarDevolucion, 'i', $facturaId);
mysqli_stmt_execute($stmtVerificarDevolucion);
$resultadoDevolucion = mysqli_stmt_get_result($stmtVerificarDevolucion);
$filaDevolucion = mysqli_fetch_assoc($resultadoDevolucion);

if ($filaDevolucion['totalDevoluciones'] > 0) {
    $_SESSION['mensaje'] = 'Error: Ya existe una devolución registrada para esta factura.';
    $_SESSION['tipo_mensaje'] = 'Error';
    header('Location: dashboardVentas.php');
    exit();
}

$totalProductosDevueltos = 0;
foreach ($productosSeleccionados as $productoId) {
    $cantidadDevolucion = (int)$cantidadesDevolucion[$productoId];

    $queryCantidadYPrecio = "
        SELECT fd.idFaDet, fd.cantidadProductoFaDet, p.precioProducto 
        FROM tb_factura_detalle fd 
        JOIN tb_productos p ON fd.producto_id = p.idProducto 
        WHERE fd.producto_id = ? AND fd.factura_cabecera_id = ?";
    $stmtCantidadYPrecio = mysqli_prepare($conection, $queryCantidadYPrecio);
    mysqli_stmt_bind_param($stmtCantidadYPrecio, 'ii', $productoId, $facturaId);
    mysqli_stmt_execute($stmtCantidadYPrecio);
    $resultCantidadYPrecio = mysqli_stmt_get_result($stmtCantidadYPrecio);
    $rowCantidadYPrecio = mysqli_fetch_assoc($resultCantidadYPrecio);

    $idFaDet = $rowCantidadYPrecio['idFaDet'];
    $cantidadActual = $rowCantidadYPrecio['cantidadProductoFaDet'];
    $precioProducto = $rowCantidadYPrecio['precioProducto'];

    if ($cantidadDevolucion > $cantidadActual) {
        $errorOcurrido = true;
        $mensaje .= "Error: La cantidad de devolución para el producto ID $productoId no puede ser mayor que la cantidad disponible. ";
        continue;
    }

    $montoTotalFactura += $cantidadDevolucion * $precioProducto;
    $totalProductosDevueltos += $cantidadDevolucion;

    $queryRegistrarDevolucion = "
        INSERT INTO tb_devoluciones (factura_cabecera_id, factura_detalle_id, fechaDevolucion, cantidadDevolucion, motivoDevolucion, condicionesDeEntrega, tipoDevolucion, tipo_nota_id) 
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)";
    $stmtRegistrarDevolucion = mysqli_prepare($conection, $queryRegistrarDevolucion);
    mysqli_stmt_bind_param($stmtRegistrarDevolucion, 'iiisssi', $facturaId, $idFaDet, $cantidadDevolucion, $motivoDevolucion, $condicionesEntrega, $tipoDevolucion, $tipoNotaId);

    if (!mysqli_stmt_execute($stmtRegistrarDevolucion)) {
        $errorOcurrido = true;
        $mensaje .= "Error: Error al registrar la devolución para el producto con el código $productoId. ";
        continue;
    }

    $queryIncrementarStock = "CALL sp_incrementar_stock_sucursal(?, ?, ?)";
    $stmtIncrementarStock = mysqli_prepare($conection, $queryIncrementarStock);
    mysqli_stmt_bind_param($stmtIncrementarStock, 'iii', $productoId, $sucursalId, $cantidadDevolucion);

    if (!mysqli_stmt_execute($stmtIncrementarStock)) {
        $errorOcurrido = true;
        $mensaje .= "Error: Error al incrementar el stock para el producto ID $productoId. ";
        continue;
    }
}

$queryFactura = "
    SELECT cliente_id FROM tb_factura_cabecera WHERE idFaCab = ?";
$stmtFactura = mysqli_prepare($conection, $queryFactura);
mysqli_stmt_bind_param($stmtFactura, 'i', $facturaId);
mysqli_stmt_execute($stmtFactura);
$resultFactura = mysqli_stmt_get_result($stmtFactura);
$rowFactura = mysqli_fetch_assoc($resultFactura);

$clienteId = $rowFactura['cliente_id'];

$queryTotalFactura = "
    SELECT SUM(cantidadProductoFaDet) AS totalCantidadFactura, SUM(subTotalFaDet) AS totalMontoFactura 
    FROM tb_factura_detalle 
    WHERE factura_cabecera_id = ?";
$stmtTotalFactura = mysqli_prepare($conection, $queryTotalFactura);
mysqli_stmt_bind_param($stmtTotalFactura, 'i', $facturaId);
mysqli_stmt_execute($stmtTotalFactura);
$resultTotalFactura = mysqli_stmt_get_result($stmtTotalFactura);
$rowTotalFactura = mysqli_fetch_assoc($resultTotalFactura);

$totalCantidadFactura = $rowTotalFactura['totalCantidadFactura'];
$montoTotalFactura = $rowTotalFactura['totalMontoFactura'];
$devolucionCompleta = ($totalProductosDevueltos == $totalCantidadFactura);
$montoNotaCredito = $devolucionCompleta ? $montoTotalFactura : $montoTotalFactura * ($totalProductosDevueltos / $totalCantidadFactura);

$queryInsertarNota = "
    INSERT INTO tb_notas_personas (fechaEmisionNota, montoNota, cliente_id, tipo_nota_id, devolucion_id, estado_nota_id) 
    VALUES (NOW(), ?, ?, ?, 
            (SELECT idDevolucion FROM tb_devoluciones WHERE factura_cabecera_id = ? ORDER BY fechaDevolucion DESC LIMIT 1), 25)";
$stmtInsertarNota = mysqli_prepare($conection, $queryInsertarNota);
mysqli_stmt_bind_param($stmtInsertarNota, 'diii', $montoNotaCredito, $clienteId, $tipoNotaId, $facturaId);

if (!mysqli_stmt_execute($stmtInsertarNota)) {
    $errorOcurrido = true;
    $mensaje .= "Error: No se pudo registrar la nota de crédito. ";
}

$queryActualizarEstadoFactura = "
    UPDATE tb_factura_cabecera
    SET estado_factura_id = 8
    WHERE idFaCab = ?";
$stmtActualizarEstadoFactura = mysqli_prepare($conection, $queryActualizarEstadoFactura);
mysqli_stmt_bind_param($stmtActualizarEstadoFactura, 'i', $facturaId);

if (!mysqli_stmt_execute($stmtActualizarEstadoFactura)) {
    $errorOcurrido = true;
    $mensaje .= "Error: No se pudo actualizar el estado de la factura. ";
}

if (!$errorOcurrido) {
    $_SESSION['mensaje'] = 'Devolución registrada correctamente y nota de crédito emitida.';
    $_SESSION['tipo_mensaje'] = 'success';
} else {
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo_mensaje'] = 'error';
}

header('Location: dashboardVentas.php');
exit();
