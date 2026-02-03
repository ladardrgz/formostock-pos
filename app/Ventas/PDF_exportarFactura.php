<?php
require('fpdf186/fpdf.php');
include_once '../modelos/conexion.php';

if (!isset($_GET['idFactura'])) {
    echo "Error: No se especificó ninguna factura.";
    exit;
}

$idFactura = (int)$_GET['idFactura'];

$query = "
    SELECT 
        tb_factura_cabecera.idFaCab,
        CONCAT(tb_personas_fisicas.nombres, '_', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
        tb_detalle_documento.valorDocumento AS numeroDocumento,
        tb_factura_cabecera.fechaDeEmisionFaCab
    FROM 
        tb_factura_cabecera
    LEFT JOIN 
        tb_clientes ON tb_factura_cabecera.cliente_id = tb_clientes.idCliente
    LEFT JOIN 
        tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN 
        tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    WHERE 
        tb_factura_cabecera.idFaCab = $idFactura
";

$result = mysqli_query($conection, $query);
$factura = mysqli_fetch_assoc($result);

if (!$factura) {
    echo "Error: Factura no encontrada.";
    exit;
}

$nombreCliente = $factura['nombreCompletoCliente'];
$numeroDocumento = $factura['numeroDocumento'];
$fechaEmision = date("j_n_y", strtotime($factura['fechaDeEmisionFaCab']));

$nombreArchivo = "Factura_N°{$idFactura}_{$nombreCliente}_{$numeroDocumento}_{$fechaEmision}.pdf";

$query = "
    SELECT 
        tb_factura_cabecera.idFaCab,
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
        tb_detalle_documento.valorDocumento AS numeroDocumento,
        tb_tipo_documentos.nombreTipoDoc AS tipoDocumento,
        tb_factura_cabecera.fechaDeEmisionFaCab,
        tb_factura_cabecera.fechaDeVencimientoFaCab,
        tb_factura_cabecera.montoTotalFaCab,
        tb_formas_pago.nombreFormaPago,
        tb_estados_logicos.nombreEstLog AS estadoFactura,
        tb_sucursal.nombreSucursal,
        tb_caja.nombreCaja, -- Agregar el nombre de la caja
        tb_factura_detalle.cantidadProductoFaDet AS cantidadProducto,
        tb_factura_detalle.subTotalFaDet AS precioTotalProducto,
        tb_productos.descripcionProducto
    FROM 
        tb_factura_cabecera
    LEFT JOIN 
        tb_clientes ON tb_factura_cabecera.cliente_id = tb_clientes.idCliente
    LEFT JOIN 
        tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN 
        tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    LEFT JOIN 
        tb_tipo_documentos ON tb_detalle_documento.tipo_documento_id = tb_tipo_documentos.idTipoDocumento
    LEFT JOIN 
        tb_formas_pago ON tb_factura_cabecera.forma_pago_id = tb_formas_pago.idFormaPago
    LEFT JOIN 
        tb_estados_logicos ON tb_factura_cabecera.estado_factura_id = tb_estados_logicos.idEstLog
    LEFT JOIN 
        tb_sucursal ON tb_factura_cabecera.sucursal_id = tb_sucursal.idSucursal
    LEFT JOIN 
        tb_caja ON tb_factura_cabecera.caja_id = tb_caja.idCaja -- Añadido para la caja
    LEFT JOIN 
        tb_factura_detalle ON tb_factura_cabecera.idFaCab = tb_factura_detalle.factura_cabecera_id
    LEFT JOIN 
        tb_productos ON tb_factura_detalle.producto_id = tb_productos.idProducto
    WHERE 
        tb_factura_cabecera.idFaCab = $idFactura
";


$result = mysqli_query($conection, $query);
$factura = mysqli_fetch_assoc($result);
$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image('../assets/img/newLogo-FormoStock.png', 10, 10, 20);

$pdf->Ln(10); 

$pdf->SetFont('Arial', 'B', 14); 
$pdf->Cell(190, 10, utf8_decode('Factura N° ' . $factura['idFaCab']), 0, 1, 'C');

$pdf->SetFillColor(80, 52, 89); 

$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial', 'B', 11); 
$pdf->Cell(190, 8, utf8_decode('Sucursal:'), 0, 1, 'C', true); 

$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 11); 
$pdf->Cell(95, 8, utf8_decode('Razón social: ' . $factura['nombreSucursal']), 0, 0); 
$pdf->Cell(95, 8, utf8_decode('Caja: ' . $factura['nombreCaja']), 0, 1);  
$pdf->Ln(1); 


$pdf->SetFillColor(80, 52, 89); 
$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial', 'B', 11); 
$pdf->Cell(190, 8, utf8_decode('Información del cliente'), 0, 1, 'C', true); 

$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 11); 

$pdf->Cell(95, 8, utf8_decode('Nombre y apellido: ' . $factura['nombreCompletoCliente']), 0, 0); 
$pdf->Cell(95, 8, utf8_decode('Documento: ' . $factura['tipoDocumento'] . ' - ' . $factura['numeroDocumento']), 0, 1); 
$pdf->Ln(1); 

$pdf->SetFillColor(80, 52, 89); 
$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial', 'B', 11); 
$pdf->Cell(190, 8, utf8_decode('Detalles de la factura'), 0, 1, 'C', true); 

$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 11); 
$pdf->Cell(95, 8, utf8_decode('Fecha de emisión: ' . $factura['fechaDeEmisionFaCab']), 0, 0);
$pdf->Cell(95, 8, utf8_decode('Fecha de vencimiento: ' . $factura['fechaDeVencimientoFaCab']), 0, 1);
$pdf->Ln(1); 

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, utf8_decode('Productos adquiridos'), 0, 1);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 10, utf8_decode('Producto'), 1);
$pdf->Cell(30, 10, utf8_decode('Cantidad'), 1);
$pdf->Cell(40, 10, utf8_decode('Precio unitario'), 1);
$pdf->Cell(50, 10, utf8_decode('Precio total'), 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);

$result = mysqli_query($conection, $query);
while ($detalle = mysqli_fetch_assoc($result)) {
    $precioUnitario = $detalle['precioTotalProducto'] / $detalle['cantidadProducto']; 

    $descripcion = utf8_decode($detalle['descripcionProducto']);
    $pdf->SetX(10); 
    $h = $pdf->GetY(); 

    $pdf->MultiCell(70, 10, $descripcion, 1);

    $nuevoY = $pdf->GetY(); 
    $alturaDescripcion = $nuevoY - $h; 

    $pdf->SetXY(10 + 70, $h); 
    $pdf->Cell(30, $alturaDescripcion, utf8_decode($detalle['cantidadProducto']), 1);
    $pdf->Cell(40, $alturaDescripcion, utf8_decode('$' . number_format($precioUnitario, 2)), 1);
    $pdf->Cell(50, $alturaDescripcion, utf8_decode('$' . number_format($detalle['precioTotalProducto'], 2)), 1);
    $pdf->Ln(); 
}

$pdf->SetFont('Arial', 'B', 12); 

$subtotal = 0;

$result = mysqli_query($conection, $query);
while ($detalle = mysqli_fetch_assoc($result)) {
    $subtotal += $detalle['precioTotalProducto']; 
}

$pdf->Cell(190, 10, utf8_decode('Subtotal: $' . number_format($subtotal, 2)), 0, 1, 'R');

$pdf->Cell(190, 10, utf8_decode('Total: $' . number_format($factura['montoTotalFaCab'], 2)), 0, 1, 'R');

$pdf->Ln(1); 

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, utf8_decode('CONDICIONES Y FORMAS DE PAGO'), 0, 1, 'C'); 

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, utf8_decode('Método de pago efectuado: ' . $factura['nombreFormaPago']), 0, 1, 'C'); 

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(190, 8, utf8_decode('Estado de la factura'), 0, 1, 'C'); 

if ($factura['estadoFactura'] === 'Pagado') {
    $pdf->SetTextColor(0, 128, 0); 
} elseif ($factura['estadoFactura'] === 'Anulado') {
    $pdf->SetTextColor(255, 0, 0); 
} else {
    $pdf->SetTextColor(0, 0, 0); 
}

$pdf->Cell(190, 10, utf8_decode(' ' . $factura['estadoFactura']), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); 
$pdf->Ln(1); 
// Salida del PDF con el nombre de archivo predefinido
$pdf->Output('D', utf8_decode($nombreArchivo));
