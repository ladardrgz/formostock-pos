<?php
include '../modelos/conexion.php';
session_start();
function iniciarFactura($conection)
{
    if (isset($_SESSION['idFacturaCabecera'])) {
        return false;
    }
    $query = "INSERT INTO tb_factura_cabecera (
        cantidadTotalFaCab, 
        fechaDeEmisionFaCab, 
        fechaDeVencimientoFaCab, 
        montoTotalFaCab, 
        cliente_id, 
        caja_id, 
        sucursal_id, 
        forma_pago_id, 
        periodo_id, 
        estado_factura_id
    ) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

    if (mysqli_query($conection, $query)) {
        $idFactura = mysqli_insert_id($conection);
        $_SESSION['idFacturaCabecera'] = $idFactura;
        return $idFactura;
    } else {
        return false;
    }
}

$idFacturaCabecera = iniciarFactura($conection);

if ($idFacturaCabecera) {
    echo json_encode([
        'success' => true,
        'idFactura' => $idFacturaCabecera
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Ya existe una factura activa.'
    ]);
}

mysqli_close($conection);
