<?php
session_start();
include_once '../modelos/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Código de cliente no válido.');
}

$clienteId = intval($_GET['id']);

// Consultar el nombre completo del cliente
$query_cliente = "
    SELECT 
        CONCAT(pf.nombres, ' ', pf.apellidos) AS nombre_completo 
    FROM 
        tb_clientes AS c
    INNER JOIN 
        tb_personas_fisicas AS pf ON c.persona_fisica_id = pf.idPersonaFisica
    WHERE 
        c.idCliente = $clienteId
";

$result_cliente = mysqli_query($conection, $query_cliente);

if ($result_cliente && mysqli_num_rows($result_cliente) > 0) {
    $cliente = mysqli_fetch_assoc($result_cliente);
    $nombre_cliente = $cliente['nombre_completo'];
} else {
    die('Cliente no encontrado.');
}

// Filtrado por fecha (Mes, Año o Día)
$fechaFiltro = '';
if (isset($_GET['mes'])) {
    $mes = intval($_GET['mes']);
    $fechaFiltro = " AND MONTH(fc.fechaDeEmisionFaCab) = $mes";
} elseif (isset($_GET['anio'])) {
    $anio = intval($_GET['anio']);
    $fechaFiltro = " AND YEAR(fc.fechaDeEmisionFaCab) = $anio";
} elseif (isset($_GET['dia'])) {
    $dia = $_GET['dia'];  // Formato: YYYY-MM-DD
    $fechaFiltro = " AND DATE(fc.fechaDeEmisionFaCab) = '$dia'";
}

// Consultar el historial de compras del cliente con filtro de fecha y forma de pago
$query_historial = "
    SELECT 
        hvc.idCompra AS id_compra, 
        hvc.factura_cabecera_id AS id_factura, 
        hvc.fechaCompra AS fecha_compra, 
        fc.fechaDeEmisionFaCab AS fecha_emision,
        fc.montoTotalFaCab AS monto_factura,
        fp.nombreFormaPago AS forma_pago
    FROM 
        tb_historial_ventas_clientes AS hvc
    INNER JOIN 
        tb_factura_cabecera AS fc ON hvc.factura_cabecera_id = fc.idFaCab
    INNER JOIN
        tb_formas_pago AS fp ON fc.forma_pago_id = fp.idFormaPago
    WHERE 
        hvc.cliente_id = $clienteId
        $fechaFiltro
    ORDER BY 
        fc.fechaDeEmisionFaCab DESC
";

$result_historial = mysqli_query($conection, $query_historial);

if ($result_historial && mysqli_num_rows($result_historial) > 0) {
    $historial = mysqli_fetch_all($result_historial, MYSQLI_ASSOC);
} else {
    $historial = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes | Historial de ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .client-info-card {
            background: linear-gradient(135deg, #5f3c77 0%, #9c78b3 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .client-info-card .client-avatar {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: white;
            color: #5f3c77;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .client-info-card .client-details {
            margin-top: 15px;
        }

        .client-info-card .client-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }

        .client-info-card .client-subtitle {
            font-size: 1rem;
            font-weight: 300;
            margin: 5px 0 0 0;
        }

        .client-info-card::before {
            content: "";
            position: absolute;
            width: 150%;
            height: 150%;
            background: rgba(255, 255, 255, 0.1);
            top: -50%;
            left: -50%;
            transform: rotate(45deg);
            pointer-events: none;
            border-radius: 50%;
        }

        .client-name {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <?php include 'nav_clientes.php'; ?>
    <div class="container mt-4">
        <div class="client-info-card text-center mb-4">
            <div class="client-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="client-details">
                <h2 class="client-name"><?php echo htmlspecialchars($nombre_cliente); ?></h2>
                <p class="client-subtitle">Historial de compras</p>
            </div>
        </div>

        <?php if (!empty($historial)) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha de emisión</th>
                        <th>Factura N°</th>
                        <th>Fecha de compra</th>
                        <th>Monto total</th>
                        <th>Forma de pago</th>
                        <th>Ver factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $registro) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['fecha_emision']); ?></td>
                            <td><?php echo htmlspecialchars($registro['id_factura']); ?></td>
                            <td><?php echo htmlspecialchars($registro['fecha_compra']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($registro['monto_factura'], 2)) . "$"; ?></td>
                            <td><?php echo htmlspecialchars($registro['forma_pago']); ?></td>
                            <td>
                                <a href="#" onclick="confirmarVerFactura('<?php echo $registro['id_factura']; ?>')">
                                    <img src="../assets/img/verFactura.png" alt="Ver factura" style="width: 34px; height: 34px;">
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No se encontraron compras para este cliente.</p>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmarVerFactura(idFactura) {
        Swal.fire({
            title: '¿Desea ver la factura?',
            text: 'Se abrirá la factura de la compra seleccionada.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir al usuario a ver la factura usando el ID de la factura
                window.location.href = `verFacturaCliente.php?id=${idFactura}`;
            }
        });
    }
</script>
</body>

</html>
