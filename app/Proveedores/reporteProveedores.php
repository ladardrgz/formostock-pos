<?php
include_once '../modelos/conexion.php';

$fechaInicio = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
$fechaFin = isset($_POST['fechaFin']) ? $_POST['fechaFin'] : '';

$query = "
    SELECT 
        pj.razonSocial AS nombreProveedor, 
        SUM(doc.cantidadProducto) AS totalProductos
    FROM 
        tb_detalle_orden_compra AS doc
    JOIN 
        tb_ordenes_compra AS oc ON doc.orden_compra_id = oc.idOrdenCompra
    JOIN 
        tb_personas_juridicas AS pj ON oc.proveedor_id = pj.idPersonaJuridica
    JOIN 
        tb_personas_fisicas AS pf ON pj.persona_fisica_id = pf.idPersonaFisica
    WHERE 
        1 = 1
";

if ($fechaInicio && $fechaFin) {
    $query .= " AND oc.fechaOrden BETWEEN '$fechaInicio' AND '$fechaFin' ";
}

$query .= "
    GROUP BY 
        pj.razonSocial
    ORDER BY 
        totalProductos DESC;
";

$result = mysqli_query($conection, $query);

$proveedores = [];
$totales = [];

while ($row = mysqli_fetch_assoc($result)) {
    $proveedores[] = $row['nombreProveedor'];
    $totales[] = $row['totalProductos'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Reporte de productos repuestos por proveedor</title>
    <style>
        body {
            background-image: url(../assets/img/background-black.png);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #9b59b6;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-form label {
            font-weight: bold;
        }

        .filter-form input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 180px;
        }

        .filter-form button {
            padding: 8px 15px;
            background-color: #9b59b6;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #7d3c98;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .report-table th,
        .report-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .report-table th {
            background-color: #9b59b6;
            color: white;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: auto;
            padding: 20px;
        }

        .chart-container canvas {
            display: block;
            width: 100%;
            max-height: 300px;
            margin: 0 auto;
        }

        .imprimirPagina {
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
        }

        .imprimirPagina img {
            width: 50px;
            height: 50px;
        }

        .table {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <?php include 'nav_proveedores.php'; ?>

    <div class="container">
        <h1>Reporte de productos repuestos por proveedor</h1>

        <form action="" method="POST" class="filter-form">
            <div>
                <label for="fechaInicio">Fecha inicio</label>
                <input type="date" name="fechaInicio" id="fechaInicio" value="<?php echo $fechaInicio; ?>">
            </div>
            <div>
                <label for="fechaFin">Fecha fin</label>
                <input type="date" name="fechaFin" id="fechaFin" value="<?php echo $fechaFin; ?>">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Total de productos repuestos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $index => $proveedor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($proveedor); ?></td>
                        <td><?php echo number_format($totales[$index], 0); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="reporteProveedores"></canvas>
        </div>

        <button class="imprimirPagina" onclick="imprimirPagina();">
            <img src="../assets/img/impresora.png" alt="Imprimir">
        </button>
    </div>

    <script>
        const labels = <?php echo json_encode($proveedores); ?>;
        const dataValues = <?php echo json_encode($totales); ?>;

        const data = {
            labels: labels,
            datasets: [{
                label: 'Productos Repuestos',
                data: dataValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'polarArea',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} productos`;
                            }
                        }
                    }
                }
            }
        };

        const reporteProveedores = new Chart(
            document.getElementById('reporteProveedores'),
            config
        );

        function imprimirPagina() {
            window.print();
        }
    </script>
</body>

</html>