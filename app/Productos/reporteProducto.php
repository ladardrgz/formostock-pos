<?php
include('../modelos/conexion.php');

function obtenerProductosVendidosPorPeriodo($idPeriodo)
{
    global $conection;
    $sql = "SELECT p.descripcionProducto, p.codigoBarrasProducto, p.numeroDeSerieProducto, 
            SUM(pp.cantidadVendidaPeriodoProducto) as cantidadVendida
            FROM tb_periodo_productos pp
            JOIN tb_factura_detalle fd ON pp.factura_detalle_id = fd.idFaDet
            JOIN tb_productos p ON fd.producto_id = p.idProducto
            JOIN tb_periodos per ON pp.periodo_id = per.idPeriodo
            WHERE per.idPeriodo = ? 
            GROUP BY p.idProducto
            ORDER BY cantidadVendida DESC";

    $stmt = mysqli_prepare($conection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idPeriodo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $productos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $productos[] = $row;
    }

    return $productos;
}

function obtenerPeriodosDisponibles()
{
    global $conection;
    $sql = "SELECT idPeriodo, nombrePeriodo FROM tb_periodos WHERE estado_periodo_id = 1";
    $result = mysqli_query($conection, $sql);

    $periodos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $periodos[] = $row;
    }

    return $periodos;
}

$productos = [];
$periodos = obtenerPeriodosDisponibles();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idPeriodo = $_POST['id_periodo'];
    $productos = obtenerProductosVendidosPorPeriodo($idPeriodo);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <title>Reporte de producto más vendido por período</title>
    <style>
        body {
            background-image: url(../assets/img/background-black.png);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #9b59b6;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            height: auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #9b59b6;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            color: #9b59b6;
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }

        .form-group select {
            padding: 12px;
            font-size: 16px;
            width: 100%;
            max-width: 350px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        .form-group select:focus {
            border-color: #9b59b6;
            outline: none;
            box-shadow: 0 0 5px rgba(155, 89, 182, 0.5);
        }

        .form-action {
            margin-top: 20px;
        }

        .form-action button {
            background-color: #9b59b6;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-action button:hover {
            background-color: #8e44ad;
        }

        .message,
        .no-results {
            font-size: 18px;
            font-weight: 600;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
            text-align: center;
        }

        .message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .no-results {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .report-table th,
        .report-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .report-table th {
            background-color: #9b59b6;
            color: white;
            text-align: center;
            font-size: 1rem;
        }

        .report-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .report-table tr:hover {
            background-color: #ecf0f1;
        }

        .report-table td {
            text-align: center;
        }

        .report-table td,
        .report-table th {
            padding: 14px 12px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            .form-group select {
                max-width: 100%;
            }
        }

        .chart-wrapper {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            max-height: 500px;
            margin: 0 auto;
        }

        .imprimirPagina {
            display: block;
            margin: 30px auto;
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
        }

        .imprimirPagina img {
            width: 60px;
            height: 60px;
        }
    </style>
</head>

<body>
    <?php include_once('nav_productos.php'); ?>
    <div class="container">
        <h2>Reporte de productos más vendidos por período</h2>
        <form id="filterForm" method="POST">
            <div class="form-group">
                <select id="id_periodo" name="id_periodo" required>
                    <option value="">Seleccione un período</option>
                    <?php foreach ($periodos as $periodo) { ?>
                        <option value="<?= $periodo['idPeriodo'] ?>"><?= $periodo['nombrePeriodo'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-action">
                <button type="submit">Ver productos más vendidos</button>
            </div>
        </form>

        <div id="noResults" class="no-results" style="display: <?= count($productos) == 0 ? 'block' : 'none' ?>">
            No se encontraron productos en el período seleccionado.
        </div>

        <table class="report-table" id="productosTable" style="display: <?= count($productos) > 0 ? 'table' : 'none' ?>">
            <thead>
                <tr>
                    <th>Descripción del producto</th>
                    <th>Código de barras</th>
                    <th>Numero de serie</th>
                    <th>Cantidad vendida</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?= $producto['descripcionProducto'] ?></td>
                        <td><?= $producto['codigoBarrasProducto'] ?></td>
                        <td><?= $producto['numeroDeSerieProducto'] ?></td>
                        <td><?= $producto['cantidadVendida'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="imprimirPagina" onclick="imprimirPagina();">
            <img src="../assets/img/impresora.png" alt="Imprimir">
        </button>
    </div>

    <div class="chart-wrapper" style="display: <?= count($productos) > 0 ? 'block' : 'none' ?>">
        <h2>Gráfico de productos más vendidos</h2>
        <div class="chart-container">
            <canvas id="productosChart"></canvas>
        </div>
    </div>

    <script>
        function imprimirPagina() {
            window.print();
        }
    </script>

    <?php if (count($productos) > 0) {
        $labels = array_map(fn($producto) => $producto['descripcionProducto'], $productos);
        $data = array_map(fn($producto) => $producto['cantidadVendida'], $productos);
    ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('productosChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: 'Cantidad vendida',
                        data: <?= json_encode($data) ?>,
                        backgroundColor: '#9b59b6',
                        borderColor: '#8e44ad',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php } ?>
</body>

</html>