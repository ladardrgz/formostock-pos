<?php

// Incluir la librería FPDF para la generación de PDF
require('../libraries/fpdf186/fpdf.php');

// Incluir el archivo de conexión a la base de datos
include_once '../modelos/conexion.php';

// Validar que se haya recibido el ID de la factura
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Código de factura no válido.');
}

$idFactura = (int)$_GET['id']; // Asegurar que el ID sea un número entero

// Consultar los detalles de la factura
$query_factura = "
    SELECT 
        fc.idFaCab AS id_factura, 
        fc.fechaDeEmisionFaCab AS fecha_emision, 
        fc.montoTotalFaCab AS monto_factura, 
        fp.nombreFormaPago AS forma_pago
    FROM 
        tb_factura_cabecera AS fc
    INNER JOIN 
        tb_formas_pago AS fp ON fc.forma_pago_id = fp.idFormaPago
    WHERE 
        fc.idFaCab = ?
";

// Preparar y ejecutar la consulta para obtener los detalles de la factura
$stmt_factura = mysqli_prepare($conection, $query_factura);
mysqli_stmt_bind_param($stmt_factura, "i", $idFactura); // Pasar el ID de la factura como parámetro
mysqli_stmt_execute($stmt_factura);
$result_factura = mysqli_stmt_get_result($stmt_factura);

// Verificar si se encontraron los datos de la factura
if (mysqli_num_rows($result_factura) == 0) {
    die('Factura no encontrada.');
}

$factura = mysqli_fetch_assoc($result_factura);

// Obtener los datos de la factura y del cliente
$queryFactura = "
    SELECT 
        tb_factura_cabecera.idFaCab,
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
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
        tb_factura_cabecera.idFaCab = ?
";

$stmt = mysqli_prepare($conection, $queryFactura);
mysqli_stmt_bind_param($stmt, "i", $idFactura); // Pasar el ID de la factura como parámetro
mysqli_stmt_execute($stmt);
$resultFactura = mysqli_stmt_get_result($stmt);

if ($facturaCliente = mysqli_fetch_assoc($resultFactura)) {
    // Extraer los datos para el nombre del archivo
    $nombreCliente = $facturaCliente['nombreCompletoCliente'];
    $numeroDocumento = $facturaCliente['numeroDocumento'];
    $fechaEmision = date("j_n_y", strtotime($facturaCliente['fechaDeEmisionFaCab']));

    // Generar nombre de archivo automáticamente usando los detalles de la factura
    $nombreArchivo = "Factura_N°{$idFactura}_{$nombreCliente}_{$numeroDocumento}_{$fechaEmision}.pdf";

    // Iniciar el PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Insertar el logo de FormoStock
    $pdf->Image('../assets/img/newLogo-FormoStock.png', 10, 10, 20);

    // Aumentar el espacio de abajo
    $pdf->Ln(10); // Reducido el espacio aquí

    // Título de la factura
    $pdf->SetFont('Arial', 'B', 14); // Tamaño de fuente reducido
    $pdf->Cell(190, 10, utf8_decode('Factura N° ' . $factura['id_factura']), 0, 1, 'C');

    // Color de fondo para los títulos
    $pdf->SetFillColor(80, 52, 89); // #503459 en RGB (80, 52, 89)

    // Sección: Información del cliente
    $pdf->SetTextColor(255, 255, 255); // Texto en blanco
    $pdf->SetFont('Arial', 'B', 11); // Tamaño de fuente reducido
    $pdf->Cell(190, 8, utf8_decode('Información del cliente'), 0, 1, 'C', true); // Título de la sección con fondo

    $pdf->SetTextColor(0, 0, 0); // Texto negro para el contenido
    $pdf->SetFont('Arial', '', 11); // Tamaño de fuente reducido

    // Línea de información del cliente
    $pdf->Cell(95, 8, utf8_decode('Nombre y apellido: ' . $facturaCliente['nombreCompletoCliente']), 0, 0); // Nombre y apellido
    $pdf->Cell(95, 8, utf8_decode('Documento: ' . $facturaCliente['numeroDocumento']), 0, 1); // Documento
    $pdf->Ln(1); // Espacio entre secciones

    // Sección: Detalles de la factura
    $pdf->SetFillColor(80, 52, 89); // Color de fondo
    $pdf->SetTextColor(255, 255, 255); // Texto en blanco
    $pdf->SetFont('Arial', 'B', 11); // Tamaño de fuente reducido
    $pdf->Cell(190, 8, utf8_decode('Detalles de la factura'), 0, 1, 'C', true); // Título de la sección con fondo

    $pdf->SetTextColor(0, 0, 0); // Texto negro para el contenido
    $pdf->SetFont('Arial', '', 11); // Tamaño de fuente reducido
    $pdf->Cell(95, 8, utf8_decode('Fecha de emisión: ' . $factura['fecha_emision']), 0, 0);
    $pdf->Cell(95, 8, utf8_decode('Monto total: $' . number_format($factura['monto_factura'], 2)), 0, 1);
    $pdf->Ln(1); // Espacio entre secciones

    // Productos adquiridos
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, utf8_decode('Productos adquiridos'), 0, 1);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(70, 10, utf8_decode('Producto'), 1);
    $pdf->Cell(30, 10, utf8_decode('Cantidad'), 1);
    $pdf->Cell(40, 10, utf8_decode('Precio unitario'), 1);
    $pdf->Cell(50, 10, utf8_decode('Precio total'), 1);
    $pdf->Ln();

    // Consultar los detalles de los productos adquiridos
    $queryDetalleFactura = "
        SELECT 
            tb_factura_detalle.cantidadProductoFaDet AS cantidadProducto,
            tb_factura_detalle.subTotalFaDet AS precioTotalProducto,
            tb_productos.descripcionProducto
        FROM 
            tb_factura_detalle
        LEFT JOIN 
            tb_productos ON tb_factura_detalle.producto_id = tb_productos.idProducto
        WHERE 
            tb_factura_detalle.factura_cabecera_id = ?
    ";

    $stmtDetalle = mysqli_prepare($conection, $queryDetalleFactura);
    mysqli_stmt_bind_param($stmtDetalle, "i", $idFactura); // Pasar el ID de la factura como parámetro
    mysqli_stmt_execute($stmtDetalle);
    $resultDetalle = mysqli_stmt_get_result($stmtDetalle);

    // Restablecer fuente para los detalles de los productos
    $pdf->SetFont('Arial', '', 10);

    while ($detalle = mysqli_fetch_assoc($resultDetalle)) {
        $precioUnitario = $detalle['precioTotalProducto'] / $detalle['cantidadProducto']; // Calcular precio unitario
        
        // Usamos MultiCell para las descripciones largas
        $pdf->MultiCell(70, 10, utf8_decode($detalle['descripcionProducto']), 1);
        $pdf->Cell(30, 10, utf8_decode($detalle['cantidadProducto']), 1);
        $pdf->Cell(40, 10, utf8_decode('$' . number_format($precioUnitario, 2)), 1);
        $pdf->Cell(50, 10, utf8_decode('$' . number_format($detalle['precioTotalProducto'], 2)), 1);
        $pdf->Ln();
    }

    // Guardar y ofrecer el PDF al usuario
    $pdf->Output('I', $nombreArchivo);

} else {
    echo "Error: No se encontró información de la factura.";
}

?>
