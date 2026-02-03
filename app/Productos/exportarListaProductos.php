<?php
session_start();
include_once('../modelos/conexion.php');
require 'vendor/autoload.php';

// Configurar la zona horaria en el script PHP
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Tu código para usar PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Array para los nombres de los meses en español
$meses = [
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre'
];

// Obtener la fecha y hora actual
$dia = date('d');
$mes = $meses[date('n')]; // Traduce el mes al español
$anio = date('Y');
$hora = date('H:i');

// Formatear la fecha y hora para el encabezado
$fecha_hora_texto = "{$dia} de {$mes} de {$anio} a las {$hora}";

// Formatear la fecha para el nombre del archivo
$fecha_actual = date('d-m-Y_H-i');

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Definir el encabezado
$sheet->setCellValue('A1', 'Lista de productos (' . $fecha_hora_texto . ')');
$sheet->setCellValue('A3', 'Descripción');
$sheet->setCellValue('B3', 'Código de barras'); // Nueva columna
$sheet->setCellValue('C3', 'Número de serie'); // Nueva columna
$sheet->setCellValue('D3', 'Precio');
$sheet->setCellValue('E3', 'Categoría');
$sheet->setCellValue('F3', 'Marca');
$sheet->setCellValue('G3', 'Impuesto');
$sheet->setCellValue('H3', 'Estado');

// Seleccionar todos los elementos de la tabla, excluyendo stockActualProducto
$consulta_productos = "
    SELECT  
        productos.descripcionProducto AS descripcion,
        productos.codigoBarrasProducto AS codigo_barras,
        productos.numeroDeSerieProducto AS numero_serie,
        productos.precioProducto AS precio,
        categorias.nombreCategoriaProducto AS categoria,
        marcas.nombreMarcaProducto AS marca,
        impuestos.valorDetalleImpuesto AS impuesto,
        estados.nombreEstLog AS estado
    FROM 
        tb_productos productos
    INNER JOIN 
        tb_categorias_productos categorias ON productos.categoria_id = categorias.idCategoriaProducto
    INNER JOIN 
        tb_marcas_productos marcas ON productos.marca_id = marcas.idMarcaProducto
    INNER JOIN 
        tb_detalle_impuestos impuestos ON productos.impuesto_id = impuestos.idDetalleImpuesto
    INNER JOIN 
        tb_estados_logicos estados ON productos.estado_producto_id = estados.idEstLog
";

$resultado_productos = mysqli_query($conection, $consulta_productos);

// Agregar los datos a la hoja de cálculo
$row = 4;
while ($row_producto = mysqli_fetch_assoc($resultado_productos)) {
    $sheet->setCellValue('A' . $row, $row_producto["descripcion"]);
    $sheet->setCellValue('B' . $row, $row_producto["codigo_barras"]); // Nueva columna
    $sheet->setCellValue('C' . $row, $row_producto["numero_serie"]); // Nueva columna
    $sheet->setCellValue('D' . $row, $row_producto["precio"]);
    $sheet->setCellValue('E' . $row, $row_producto["categoria"]);
    $sheet->setCellValue('F' . $row, $row_producto["marca"]);
    $sheet->setCellValue('G' . $row, $row_producto["impuesto"]);
    $sheet->setCellValue('H' . $row, $row_producto["estado"]);
    $row++;
}

// Configuración de la cabecera para evitar el almacenamiento en caché del archivo
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"Productos_Exportacion_Fecha_{$fecha_actual}.xlsx\"");
header("Cache-Control: max-age=0");

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
