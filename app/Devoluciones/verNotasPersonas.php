<?php
include_once '../modelos/conexion.php';

if (!isset($_GET['id'])) {
    echo "Error: No se especificó ninguna devolución.";
    exit;
}

$idDevolucion = (int)$_GET['id'];

$queryDevolucion = "
    SELECT 
        tb_devoluciones.idDevolucion,
        tb_devoluciones.tipoDevolucion,
        tb_devoluciones.fechaDevolucion,
        tb_devoluciones.cantidadDevolucion,
        tb_devoluciones.motivoDevolucion,
        tb_devoluciones.condicionesDeEntrega,
        tb_factura_cabecera.idFaCab AS factura_id,
        tb_factura_cabecera.fechaDeEmisionFaCab,
        tb_factura_cabecera.fechaDeVencimientoFaCab,
        tb_factura_cabecera.montoTotalFaCab,
        tb_estados_logicos.nombreEstLog AS estadoFactura,
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombreCompletoCliente,
        tb_tipo_documentos.nombreTipoDoc AS tipoDocumento,
        tb_detalle_documento.valorDocumento AS numeroDocumento,
        tb_formas_pago.nombreFormaPago
    FROM 
        tb_devoluciones
    LEFT JOIN 
        tb_factura_cabecera ON tb_devoluciones.factura_cabecera_id = tb_factura_cabecera.idFaCab
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
    WHERE 
        tb_devoluciones.idDevolucion = $idDevolucion
";

$resultDevolucion = mysqli_query($conection, $queryDevolucion);

if (!$resultDevolucion || mysqli_num_rows($resultDevolucion) == 0) {
    echo "Devolución no encontrada.";
    exit;
}

$devolucion = mysqli_fetch_assoc($resultDevolucion);
$idFactura = $devolucion['factura_id'];

$queryNotas = "
    SELECT 
        tb_notas_personas.fechaEmisionNota,
        tb_notas_personas.montoNota,
        tb_tipos_notas.tipoNota AS tipoNota,
        tb_estados_logicos.nombreEstLog AS estadoNota
    FROM 
        tb_notas_personas
    LEFT JOIN 
        tb_tipos_notas ON tb_notas_personas.tipo_nota_id = tb_tipos_notas.idTipoNota
    LEFT JOIN 
        tb_estados_logicos ON tb_notas_personas.estado_nota_id = tb_estados_logicos.idEstLog
    WHERE 
        tb_notas_personas.devolucion_id = $idDevolucion
";

$resultNotas = mysqli_query($conection, $queryNotas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Previsualización de devolución</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background: url('../assets/img/background-black.png') repeat center center fixed;
        }

        nav {
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .table th {
            width: 30%;
            background-color: #503459;
            color: #fff;
            font-weight: bold;
            text-align: left;
        }

        .table td {
            background-color: #f1f1f1;
            color: #343a40;
        }

        .table-striped thead th {
            background-color: #503459;
            color: #fff;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e9ecef;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .table th,
            .table td {
                font-size: 14px;
            }

            .navbar a {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <?php include 'nav_devoluciones.php'; ?>
    <div class="container" id="content">
        <h2>Resumen de la devolución</h2>
        <table class="table table-striped">
            <tr>
                <th>Tipo de devolución</th>
                <td><?php echo $devolucion['tipoDevolucion']; ?></td>
            </tr>
            <tr>
                <th>Fecha de devolución</th>
                <td><?php echo $devolucion['fechaDevolucion']; ?></td>
            </tr>
            <tr>
                <th>Cantidad devuelta</th>
                <td><?php echo $devolucion['cantidadDevolucion']; ?></td>
            </tr>
            <tr>
                <th>Motivo de la devolución</th>
                <td><?php echo $devolucion['motivoDevolucion']; ?></td>
            </tr>
            <tr>
                <th>Condiciones de entrega</th>
                <td><?php echo $devolucion['condicionesDeEntrega']; ?></td>
            </tr>
        </table>

        <h2>Información de la factura vinculada a la devolución</h2>
        <table class="table table-striped">
            <tr>
                <th>Fecha de emisión</th>
                <td><?php echo $devolucion['fechaDeEmisionFaCab']; ?></td>
            </tr>
            <tr>
                <th>Fecha de vencimiento</th>
                <td><?php echo $devolucion['fechaDeVencimientoFaCab']; ?></td>
            </tr>
            <tr>
                <th>Monto total</th>
                <td><?php echo number_format($devolucion['montoTotalFaCab'], 2); ?></td>
            </tr>
            <tr>
                <th>Estado de la factura</th>
                <td><?php echo $devolucion['estadoFactura']; ?></td>
            </tr>
            <tr>
                <th>Cliente</th>
                <td><?php echo $devolucion['nombreCompletoCliente']; ?></td>
            </tr>
            <tr>
                <th>Tipo de documento</th>
                <td><?php echo $devolucion['tipoDocumento']; ?></td>
            </tr>
            <tr>
                <th>Número de documento</th>
                <td><?php echo $devolucion['numeroDocumento']; ?></td>
            </tr>
            <tr>
                <th>Forma de pago efectuada</th>
                <td><?php echo $devolucion['nombreFormaPago']; ?></td>
            </tr>
        </table>

        <h2>Notas de crédito o débito asociadas a la devolución</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha de emisión</th>
                    <th>Monto</th>
                    <th>Tipo de nota</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($nota = mysqli_fetch_assoc($resultNotas)) { ?>
                    <tr>
                        <td><?php echo $nota['fechaEmisionNota']; ?></td>
                        <td><?php echo number_format($nota['montoNota'], 2); ?></td>
                        <td><?php echo $nota['tipoNota']; ?></td>
                        <td><?php echo $nota['estadoNota']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</body>

</html>