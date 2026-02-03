<?php
session_start();
include '../modelos/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['productos']) && !empty($data['total']) && !empty($data['forma_pago'])) {
    mysqli_begin_transaction($conection);
    try {
        if (!isset($_SESSION['caja_id']) || !isset($_SESSION['sucursal_id'])) {
            throw new Exception('No se ha establecido el código de caja o sucursal en la sesión.');
        }

        $caja_id = $_SESSION['caja_id'];
        $sucursal_id = $_SESSION['sucursal_id'];
        $cliente_id = isset($data['cliente_id']) ? $data['cliente_id'] : null;

        $fechaActual = date('Y-m-d');
        $queryPeriodo = "SELECT idPeriodo FROM tb_periodos WHERE ? BETWEEN fechaInicioPeriodo AND fechaFinPeriodo LIMIT 1";
        $stmtPeriodo = mysqli_prepare($conection, $queryPeriodo);
        if (!$stmtPeriodo) {
            throw new Exception('Error al preparar la consulta de período: ' . mysqli_error($conection));
        }
        mysqli_stmt_bind_param($stmtPeriodo, 's', $fechaActual);
        mysqli_stmt_execute($stmtPeriodo);
        $resultPeriodo = mysqli_stmt_get_result($stmtPeriodo);

        if ($resultPeriodo && $rowPeriodo = mysqli_fetch_assoc($resultPeriodo)) {
            $periodo_id = $rowPeriodo['idPeriodo'];
        } else {
            throw new Exception('No se encontró un período correspondiente para la fecha actual.');
        }

        if (!isset($_SESSION['idFacturaCabecera'])) {
            if ($cliente_id === null) {
                $queryCabecera = "INSERT INTO tb_factura_cabecera (
                    cantidadTotalFaCab, fechaDeEmisionFaCab, fechaDeVencimientoFaCab, montoTotalFaCab, cliente_id, caja_id, sucursal_id, forma_pago_id, estado_factura_id, periodo_id
                ) VALUES (?, NOW(), NOW(), ?, NULL, ?, ?, ?, ?, ?)";
            } else {
                $queryCabecera = "INSERT INTO tb_factura_cabecera (
                    cantidadTotalFaCab, fechaDeEmisionFaCab, fechaDeVencimientoFaCab, montoTotalFaCab, cliente_id, caja_id, sucursal_id, forma_pago_id, estado_factura_id, periodo_id
                ) VALUES (?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?)";
            }

            $stmtCabecera = mysqli_prepare($conection, $queryCabecera);
            if (!$stmtCabecera) {
                throw new Exception('Error en la preparación de la consulta para la cabecera: ' . mysqli_error($conection));
            }

            $cantidadTotal = count($data['productos']);
            $total = $data['total'];
            $forma_pago_id = $data['forma_pago'];
            $estado_factura_id = 10;

            if ($cliente_id === null) {
                mysqli_stmt_bind_param($stmtCabecera, 'diiiii', $cantidadTotal, $total, $caja_id, $sucursal_id, $forma_pago_id, $estado_factura_id, $periodo_id);
            } else {
                mysqli_stmt_bind_param($stmtCabecera, 'diiiiiii', $cantidadTotal, $total, $cliente_id, $caja_id, $sucursal_id, $forma_pago_id, $estado_factura_id, $periodo_id);
            }

            if (!mysqli_stmt_execute($stmtCabecera)) {
                throw new Exception('Error al ejecutar la consulta de la cabecera: ' . mysqli_stmt_error($stmtCabecera));
            }

            $idFactura = mysqli_insert_id($conection);
            if (!$idFactura) {
                throw new Exception('Error al obtener el ID de la factura: ' . mysqli_error($conection));
            }

            $montoTransaccionPago = $data['total'];
            $fechaTransaccionPago = date('Y-m-d');

            $queryTransaccionPago = "INSERT INTO tb_transacciones_pago_caja (caja_id, forma_pago_id, montoTransaccionPago, fechaTransaccionPago)
                                    VALUES (?, ?, ?, ?)";
            $stmtTransaccionPago = mysqli_prepare($conection, $queryTransaccionPago);
            if (!$stmtTransaccionPago) {
                throw new Exception('Error en la preparación de la consulta para las transacciones de pago: ' . mysqli_error($conection));
            }

            mysqli_stmt_bind_param($stmtTransaccionPago, 'iiis', $caja_id, $forma_pago_id, $montoTransaccionPago, $fechaTransaccionPago);

            if (!mysqli_stmt_execute($stmtTransaccionPago)) {
                throw new Exception('Error al ejecutar la consulta de la transacción de pago: ' . mysqli_stmt_error($stmtTransaccionPago));
            }
        }

        foreach ($data['productos'] as $producto) {
            $queryDetalle = "INSERT INTO tb_factura_detalle (factura_cabecera_id, producto_id, cantidadProductoFaDet, subTotalFaDet)
            VALUES (?, ?, ?, ?)";

            $stmtDetalle = mysqli_prepare($conection, $queryDetalle);
            if (!$stmtDetalle) {
                throw new Exception('Error en la preparación de la consulta para el detalle: ' . mysqli_error($conection));
            }

            $producto_id = $producto['id'];
            $cantidadProducto = $producto['cantidad'];
            $subTotal = $producto['precio'] * $producto['cantidad'];
            mysqli_stmt_bind_param($stmtDetalle, 'iiii', $idFactura, $producto_id, $cantidadProducto, $subTotal);

            if (!mysqli_stmt_execute($stmtDetalle)) {
                throw new Exception('Error al ejecutar la consulta del detalle: ' . mysqli_stmt_error($stmtDetalle));
            }

            $idDetalleFactura = mysqli_insert_id($conection);
            if (!$idDetalleFactura) {
                throw new Exception('Error al obtener el ID del detalle de la factura: ' . mysqli_error($conection));
            }

            $queryPeriodoProducto = "INSERT INTO tb_periodo_productos (periodo_id, factura_detalle_id, cantidadVendidaPeriodoProducto)
            VALUES (?, ?, ?)";

            $stmtPeriodoProducto = mysqli_prepare($conection, $queryPeriodoProducto);
            if (!$stmtPeriodoProducto) {
                throw new Exception('Error en la preparación de la consulta para el período-producto: ' . mysqli_error($conection));
            }

            mysqli_stmt_bind_param($stmtPeriodoProducto, 'iii', $periodo_id, $idDetalleFactura, $cantidadProducto);

            if (!mysqli_stmt_execute($stmtPeriodoProducto)) {
                throw new Exception('Error al ejecutar la consulta para el período-producto: ' . mysqli_stmt_error($stmtPeriodoProducto));
            }
        }

        mysqli_commit($conection);
        unset($_SESSION['idFacturaCabecera']);

        echo json_encode([
            'success' => true,
            'idFactura' => $idFactura
        ]);
    } catch (Exception $e) {
        mysqli_rollback($conection);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos'
    ]);
}

mysqli_close($conection);
