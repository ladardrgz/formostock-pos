<?php
include_once '../modelos/conexion.php';

$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

$query = "
    SELECT 
        CONCAT(pf.nombres, ' ', pf.apellidos) AS nombreCliente, 
        COUNT(fc.idFaCab) AS cantidadCompras,
        SUM(fc.montoTotalFaCab) AS totalGastado
    FROM 
        tb_factura_cabecera AS fc
    JOIN 
        tb_clientes AS c ON fc.cliente_id = c.idCliente
    JOIN 
        tb_personas_fisicas AS pf ON c.persona_fisica_id = pf.idPersonaFisica
    WHERE 
        (fc.fechaDeEmisionFaCab BETWEEN ? AND ?)
    GROUP BY 
        fc.cliente_id
    ORDER BY 
        totalGastado DESC
    LIMIT 10;
";

$stmt = mysqli_prepare($conection, $query);
if ($fechaInicio && $fechaFin) {
    mysqli_stmt_bind_param($stmt, 'ss', $fechaInicio, $fechaFin);
} else {
    $defaultStart = '1900-01-01';
    $defaultEnd = '2100-12-31';
    mysqli_stmt_bind_param($stmt, 'ss', $defaultStart, $defaultEnd);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$clientes = [];
$cantidades = [];
$listado = [];
while ($row = mysqli_fetch_assoc($result)) {
    $clientes[] = $row['nombreCliente'];
    $cantidades[] = $row['cantidadCompras'];
    $listado[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Clientes - Compras</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style_nav_module.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
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
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #9b59b6;
        font-size: 1.8rem;
        margin-bottom: 20px;
        text-align: center;
    }

    .filter-form {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-form input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
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
        max-width: 300px;
        margin: 20px auto;
    }

    .chart-container canvas {
        display: block;
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
    <?php include('nav_clientes.php'); ?>
    <div class="container">
        <h2>Reporte de clientes - Top compradores</h2>
        <!-- Formulario para filtrar -->
        <form class="filter-form" method="GET" action="">
            <label for="fechaInicio">Desde:</label>
            <input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo htmlspecialchars($fechaInicio); ?>" required>
            <label for="fechaFin">Hasta:</label>
            <input type="date" id="fechaFin" name="fechaFin" value="<?php echo htmlspecialchars($fechaFin); ?>" required>
            <button type="submit">Filtrar</button>
        </form>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Cantidad de compras realizadas</th>
                    <th>Total gastado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listado as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nombreCliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['cantidadCompras']); ?></td>
                        <td>$<?php echo number_format($cliente['totalGastado'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="reporteClientes"></canvas>
        </div>

        <button class="imprimirPagina" onclick="imprimirPagina();">
            <img src="../assets/img/impresora.png" alt="Imprimir">
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function imprimirPagina() {
            window.print(); 
        }
    </script>
    <script>
        const labels = <?php echo json_encode($clientes); ?>;
        const dataValues = <?php echo json_encode($cantidades); ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: 'Cantidad de Compras',
                data: dataValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} compras`;
                            }
                        }
                    }
                }
            }
        };

        const reporteClientes = new Chart(
            document.getElementById('reporteClientes'),
            config
        );
    </script>

</body>

</html>
