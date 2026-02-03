<?php
// Incluir el autoload de Composer
require '../libraries/vendor/autoload.php'; // Asegúrate de que el archivo autoload.php esté en el directorio correcto

// Incluir la conexión a la base de datos
include '../modelos/conexion.php';

// Incluir FPDF
require('../libraries/fpdf/fpdf.php');

// Obtener datos básicos de la caja
$caja_id = 1; // ID de la caja que queremos analizar

$query_caja = "SELECT saldoInicialCaja, saldoActualCaja, montoArqueoCaja, fechaAperturaCaja, fechaCierreCaja, nombreCaja 
               FROM tb_caja WHERE idCaja = $caja_id";
$result_caja = mysqli_query($conection, $query_caja);
$caja_data = mysqli_fetch_assoc($result_caja);

// Consultar las transacciones realizadas en la caja (ingresos y egresos)
$query_transacciones = "SELECT tp.forma_pago_id, SUM(tp.montoTransaccionPago) AS montoTotal
                        FROM tb_transacciones_pago_caja tp
                        WHERE tp.caja_id = $caja_id
                        GROUP BY tp.forma_pago_id";
$result_transacciones = mysqli_query($conection, $query_transacciones);

// Consultar las formas de pago
$query_formas_pago = "SELECT * FROM tb_formas_pago";
$result_formas_pago = mysqli_query($conection, $query_formas_pago);

// Inicializamos un arreglo para almacenar el monto por cada forma de pago
$formas_pago = [];
while ($row = mysqli_fetch_assoc($result_formas_pago)) {
    $formas_pago[$row['idFormaPago']] = [
        'nombre' => $row['nombreFormaPago'],
        'monto' => 0
    ];
}

// Asignamos los montos a las formas de pago correspondientes
while ($row = mysqli_fetch_assoc($result_transacciones)) {
    $forma_pago_id = $row['forma_pago_id'];
    $montototal = $row['montoTotal'];
    $formas_pago[$forma_pago_id]['monto'] = $montototal;
}

// Consultar los ingresos totales
$query_ingresos = "SELECT SUM(montoTransaccionPago) AS total_ingresos 
                   FROM tb_transacciones_pago_caja 
                   WHERE caja_id = $caja_id AND montoTransaccionPago > 0";
$result_ingresos = mysqli_query($conection, $query_ingresos);
$ingresos = mysqli_fetch_assoc($result_ingresos)['total_ingresos'] ?? 0;

// Calcular el arqueo
$saldo_inicial = $caja_data['saldoInicialCaja'];
$saldo_actual = $caja_data['saldoActualCaja'];
$diferencia = $saldo_actual - ($saldo_inicial + $ingresos); // Solo se suman los ingresos

// Exportar a Excel
if (isset($_GET['exportar']) && $_GET['exportar'] == 'excel') {
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Agregar el nombre de la caja
    $sheet->setCellValue('A1', 'Nombre de la caja');
    $sheet->setCellValue('B1', $caja_data['nombreCaja']);
    
    // Agregar los detalles del reporte
    $sheet->setCellValue('A2', 'Concepto');
    $sheet->setCellValue('B2', 'Monto');

    $sheet->setCellValue('A3', 'Saldo inicial de caja');
    $sheet->setCellValue('B3', $saldo_inicial);
    $sheet->setCellValue('A4', 'Ingresos totales');
    $sheet->setCellValue('B4', $ingresos);
    $sheet->setCellValue('A5', 'Saldo final de caja');
    $sheet->setCellValue('B5', $saldo_actual);
    $sheet->setCellValue('A6', 'Diferencia');
    $sheet->setCellValue('B6', $diferencia);

    $sheet->setCellValue('A8', 'Formas de pago');
    $sheet->setCellValue('B8', 'Monto');

    $row = 9;
    foreach ($formas_pago as $forma) {
        $sheet->setCellValue('A' . $row, $forma['nombre']);
        $sheet->setCellValue('B' . $row, $forma['monto']);
        $row++;
    }

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Reporte_Arqueo_Caja' . date('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}


if (isset($_GET['exportar']) && $_GET['exportar'] == 'pdf') {

    // Crear una nueva instancia de FPDF
    $pdf = new FPDF();
    
    // Establecer la codificación del documento a UTF-8
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12); // Arial tiene soporte básico para caracteres especiales

    // Definir la codificación de caracteres a UTF-8
    $pdf->SetTextColor(0, 0, 0); // Color negro para el texto

    // Título del reporte
    $pdf->Cell(200, 10, 'Reporte de Arqueo de Caja', 0, 1, 'C');
    
    // Detalles del arqueo
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Nombre de la caja: ' . $caja_data['nombreCaja'], 0, 1);  // Agregar el nombre de la caja
    $pdf->Cell(100, 10, 'Fecha del arqueo: ' . date('Y-m-d'), 0, 1);
    $pdf->Cell(100, 10, 'Hora de inicio: ' . $caja_data['fechaAperturaCaja'], 0, 1);
    $pdf->Cell(100, 10, 'Hora de cierre: ' . $caja_data['fechaCierreCaja'], 0, 1);
    
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Saldo inicial de caja: $' . number_format($saldo_inicial, 2), 0, 1);
    $pdf->Cell(100, 10, 'Ingresos totales: $' . number_format($ingresos, 2), 0, 1);
    $pdf->Cell(100, 10, 'Saldo final de caja: $' . number_format($saldo_actual, 2), 0, 1);
    $pdf->Cell(100, 10, 'Diferencia: $' . number_format($diferencia, 2), 0, 1);
    
    // Espacio para la tabla de formas de pago
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Formas de pago', 0, 1);
    
    // Encabezados de la tabla
    $pdf->SetFillColor(200, 220, 255); // Color de fondo de las celdas
    $pdf->Cell(95, 10, 'Nombre', 1, 0, 'C', true);
    $pdf->Cell(95, 10, 'Monto', 1, 1, 'C', true);
    
    // Contenido de la tabla
    foreach ($formas_pago as $forma) {
        $pdf->Cell(95, 10, utf8_decode($forma['nombre']), 1, 0, 'C');
        $pdf->Cell(95, 10, '$' . number_format($forma['monto'], 2), 1, 1, 'C');
    }
    
    // Firmas
    $pdf->Ln(10);
    $pdf->Cell(100, 10, 'Firma del responsable: ____________________________', 0, 1);
    $pdf->Cell(100, 10, 'Firma del supervisor: ____________________________', 0, 1);
    
    // Output del archivo PDF (para descarga directa)
    $pdf->Output('D', 'Reporte_Arqueo_Caja.pdf'); // 'D' significa descarga automática
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Arqueo de caja</title>
    <style>
        html,
        body {
            background-image: url('../assets/img/background-black.png');
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
            overflow-y: auto;
            background-color: #f4f4f4;
            border-radius: 8px;
            width: 50%;
        }

        h1 {
            text-align: center;
            color: #503459;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            background-color: #fff;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.1em;
            color: #503459;
        }

        .signature-container {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-container div {
            text-align: center;
            width: 45%;
        }

        .export-button a {
            text-align: center;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            background-color: transparent;
            color: #2E7D32;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: none;
            border: 2px solid #2E7D32;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .export-button a:hover {
            background-color: #2E7D32;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .export-button a img {
            width: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }

        /* Estilo específico para el botón de Exportar a Excel */
        #exportar-excel {
            color: #2E7D32;
            border: 2px solid #2E7D32;
        }

        #exportar-excel:hover {
            background-color: #2E7D32;
            color: white;
        }

        /* Estilo específico para el botón de Exportar a PDF */
        #exportar-pdf {
            color: #E15F78;
            border: 2px solid #E15F78;
        }

        #exportar-pdf:hover {
            background-color: #E15F78;
            color: white;
        }
    </style>
</head>

<body>
    <?php include('MenuNavegacionCaja.php'); ?>
    <div class="container" id="container">
        <div>
            <h1>Arqueo de caja</h1>
            <p><strong>Fecha del arqueo:</strong> <?= date('Y-m-d') ?></p>
            <p><strong>Hora de inicio:</strong> <?= $caja_data['fechaAperturaCaja'] ?></p>
            <p><strong>Hora de cierre:</strong> <?= $caja_data['fechaCierreCaja'] ?></p>

            <table>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
                <tr>
                    <td>Saldo inicial de caja</td>
                    <td>$<?= number_format($saldo_inicial, 2) ?></td>
                </tr>
                <tr>
                    <td>Ingresos totales</td>
                    <td>$<?= number_format($ingresos, 2) ?></td>
                </tr>
                <tr>
                    <td><strong>Saldo final de caja</strong></td>
                    <td><strong>$<?= number_format($saldo_actual, 2) ?></strong></td>
                </tr>
            </table>

            <div class="section-title">Formas de pago</div>
            <table>
                <tr>
                    <th>Forma de pago</th>
                    <th>Monto</th>
                </tr>
                <?php foreach ($formas_pago as $forma): ?>
                    <tr>
                        <td><?= $forma['nombre'] ?></td>
                        <td>$<?= number_format($forma['monto'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="export-button" style="text-align: center; margin-top: 20px;">
                <a href="#" class="btn btn-success" id="exportar-excel">
                    <img src="../assets/img/excelExportacion.png" alt="Exportar a Excel">
                    Exportar a Excel
                </a>
                <a href="#" class="btn btn-danger" id="exportar-pdf">
                    <img src="../assets/img/pdf.png" alt="Exportar a PDF">
                    Exportar a PDF
                </a>
            </div>

            <div class="signature-container">
                <div>
                    <p><strong>Firma del responsable</strong></p>
                    <p>__________________________</p>
                </div>
                <div>
                    <p><strong>Firma del supervisor</strong></p>
                    <p>__________________________</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar SweetAlert2 CDN en la sección <head> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Función para manejar la exportación
            function exportar(tipo) {
                // SweetAlert confirmación
                let mensaje = "";
                if (tipo === 'excel') {
                    mensaje = "¿Deseas exportar los datos a Excel?";
                } else if (tipo === 'pdf') {
                    mensaje = "¿Deseas exportar los datos a PDF?";
                }

                Swal.fire({
                    title: mensaje,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6181e7',
                    cancelButtonColor: '#cd4646',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si confirma, redirigir a la URL para exportar
                        window.location.href = `?exportar=${tipo}`;
                    }
                });
            }

            // Obtener los botones de exportación
            const excelBtn = document.getElementById('exportar-excel');
            const pdfBtn = document.getElementById('exportar-pdf');

            // Asignar eventos a los botones
            if (excelBtn) {
                excelBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Evita el enlace por defecto
                    exportar('excel'); // Exportar a Excel
                });
            }

            if (pdfBtn) {
                pdfBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Evita el enlace por defecto
                    exportar('pdf'); // Exportar a PDF
                });
            }
        });
    </script>

</body>

</html>