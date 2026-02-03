<?php
include_once '../modelos/conexion.php';
if (!isset($_GET['id'])) {
    echo "Error: No se especificó ninguna factura.";
    exit;
}
$idFactura = (int)$_GET['id'];
$query = "
    SELECT 
        tb_factura_cabecera.idFaCab,
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
        tb_tipo_documentos.nombreTipoDoc AS tipoDocumento,
        tb_detalle_documento.valorDocumento AS numeroDocumento,
        tb_factura_cabecera.fechaDeEmisionFaCab,
        tb_factura_cabecera.fechaDeVencimientoFaCab,
        tb_factura_cabecera.montoTotalFaCab,
        tb_formas_pago.nombreFormaPago,
        tb_estados_logicos.nombreEstLog AS estadoFactura,
        tb_factura_detalle.cantidadProductoFaDet AS cantidad,
        tb_factura_detalle.subTotalFaDet AS subTotal,
        tb_productos.descripcionProducto AS nombreProducto,
        tb_productos.precioProducto AS precioUnitario,
        tb_factura_detalle.cantidadProductoFaDet * tb_productos.precioProducto AS precio_total,  -- Calcular el precio total
        tb_tipo_contacto.nombreTipoContacto AS tipoContacto,
        tb_detalle_contacto.valorDetalleContacto AS contactoCliente,
        CONCAT(d.descripcionDomicilio, ', ', b.nombreBarrio, ', ', l.nombreLocalidad, ', ', p.nombreProvincia, ', ', pa.nombrePais) AS direccionSucursal
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
        tb_detalle_contacto ON tb_personas_fisicas.detalle_contacto_id = tb_detalle_contacto.idDetalleContacto
    LEFT JOIN 
        tb_tipo_contacto ON tb_detalle_contacto.tipo_contacto_id = tb_tipo_contacto.idTipoContacto
    LEFT JOIN 
        tb_formas_pago ON tb_factura_cabecera.forma_pago_id = tb_formas_pago.idFormaPago
    LEFT JOIN 
        tb_estados_logicos ON tb_factura_cabecera.estado_factura_id = tb_estados_logicos.idEstLog
    LEFT JOIN 
        tb_factura_detalle ON tb_factura_cabecera.idFaCab = tb_factura_detalle.factura_cabecera_id
    LEFT JOIN 
        tb_productos ON tb_factura_detalle.producto_id = tb_productos.idProducto
    LEFT JOIN 
        tb_sucursal AS s ON tb_factura_cabecera.sucursal_id = s.idSucursal
    LEFT JOIN 
        tb_personas_juridicas AS pj ON s.persona_juridica_id = pj.idPersonaJuridica
    LEFT JOIN 
        tb_personas_fisicas AS pf ON pj.persona_fisica_id = pf.idPersonaFisica
    LEFT JOIN 
        tb_domicilios_personas AS dp ON pf.idPersonaFisica = dp.persona_fisica_id
    LEFT JOIN 
        tb_domicilios AS d ON dp.domicilio_id = d.idDomicilio
    LEFT JOIN 
        tb_barrios AS b ON d.barrio_id = b.idBarrio
    LEFT JOIN 
        tb_localidades AS l ON b.localidad_id = l.idLocalidad
    LEFT JOIN 
        tb_provincias AS p ON l.provincia_id = p.idProvincia
    LEFT JOIN 
        tb_paises AS pa ON p.pais_id = pa.idPais
    WHERE 
        tb_factura_cabecera.idFaCab = $idFactura
";
$result = mysqli_query($conection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Factura no encontrada.";
    exit;
}

$factura = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Previsualización de factura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background-image: url('../assets/img/background-black.png');
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .company-details,
        .client-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .company-details img {
            max-height: 60px;
        }

        .details p {
            margin: 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            position: relative;
            z-index: 2;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #6c4e75;
            color: white;
        }

        .subtotal {
            margin-top: 10px;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            z-index: 2;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            z-index: 2;
        }

        .note {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            z-index: 2;
        }

        .btn-primary {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {

            .company-details,
            .client-details {
                flex-direction: column;
            }
        }

        .sello-factura {
            font-family: 'Merriweather', serif;
            position: absolute;
            top: 15%;
            left: 50%;
            transform: translateX(-50%) rotate(-45deg);
            width: 150%;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
        }

        .sello-factura.pagada {
            color: rgba(0, 128, 0, 0.5);
        }

        .sello-factura.anulada {
            color: rgba(255, 0, 0, 0.5);
        }

        .exportar-form {
            position: relative;
            z-index: 10000;
        }

        .note {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'nav_ventas.php'; ?>
    <div class="container">
        <div class="sello-factura <?php echo ($factura['estadoFactura'] === 'Pagado') ? 'pagada' : (($factura['estadoFactura'] === 'Anulado') ? 'anulada' : ''); ?>">
            <?php
            if ($factura['estadoFactura'] === 'Pagado') {
                echo "FACTURA<br>PAGADA";
            } elseif ($factura['estadoFactura'] === 'Anulado') {
                echo "FACTURA<br>ANULADA";
            } else {
                echo "FACTURA<br>PENDIENTE";
            }
            ?>
        </div>

        <div class="company-details">
            <div class="details">
                <h2>FormoStock</h2>
                <p><strong>Dirección: </strong><?php echo $factura['direccionSucursal']; ?></p>
                <p><strong>Teléfono: </strong>(+54) 0 3704 - 763092</p>
                <p><strong>Correo electrónico: </strong>formostock@gmail.com</p>
            </div>
            <div>
                <img src="../assets/img/newLogo-FormoStock.png" alt="Logotipo de la empresa">
            </div>
        </div>

        <div class="client-details">
            <div class="details">
                <p><strong>Cliente:</strong> <?php echo $factura['nombreCompletoCliente']; ?></p>
                <p><strong>Tipo de documento:</strong> <?php echo $factura['tipoDocumento']; ?></p>
                <p><strong>Número de documento:</strong> <?php echo $factura['numeroDocumento']; ?></p>
                <p><strong>Contacto:</strong> <?php echo $factura['contactoCliente']; ?></p>
            </div>
            <div class="details">
                <p><strong>Factura N°</strong> <?php echo $factura['idFaCab']; ?></p>
                <p><strong>Fecha de emisión:</strong> <?php echo $factura['fechaDeEmisionFaCab']; ?></p>
                <p><strong>Fecha de vencimiento:</strong> <?php echo $factura['fechaDeVencimientoFaCab']; ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Precio total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                mysqli_data_seek($result, 0);
                while ($detalle = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$detalle['nombreProducto']}</td>";
                    echo "<td>{$detalle['cantidad']}</td>";
                    echo "<td>$" . number_format($detalle['precioUnitario'], 2) . "</td>";
                    echo "<td>$" . number_format($detalle['precio_total'], 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="subtotal">Subtotal: $<?php echo number_format($factura['subTotal'], 2); ?></p>
        <p class="total">Monto total: $<?php echo number_format($factura['montoTotalFaCab'], 2); ?></p>
        <form id="exportarForm" action="exportarFacturaPDF.php" method="POST" class="exportar-form">
            <input type="hidden" name="idFactura" value="<?php echo $factura['idFaCab']; ?>">
            <button type="button" onclick="exportarFactura()" class="btn-primary" style="border: none; background: none;">
                <img src="../assets/img/pdf.png" alt="Exportar a PDF" style="width: 50px;">
            </button>
        </form>

        <script>
            function exportarFactura() {
                Swal.fire({
                    title: '¿Desea descargar la factura en PDF?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6181e7',
                    cancelButtonColor: '#cd4646',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('exportarForm');

                        if (!form) {
                            console.error("Formulario 'exportarForm' no encontrado.");
                            return;
                        }

                        const inputNombreArchivo = document.createElement('input');
                        inputNombreArchivo.type = 'hidden';
                        inputNombreArchivo.name = 'nombreArchivo';

                        const idFactura = form.querySelector('input[name="idFactura"]').value;
                        const nombreCliente = "<?php echo $factura['nombreCompletoCliente']; ?>";

                        inputNombreArchivo.value = `Factura_N°${idFactura}_${nombreCliente.replace(/\s+/g, '_')}.pdf`;

                        form.appendChild(inputNombreArchivo);
                        form.submit();
                    }
                });
            }
        </script>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html