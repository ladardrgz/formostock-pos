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
$sheet->setCellValue('A1', 'Lista de sucursales (' . $fecha_hora_texto . ')');
$sheet->setCellValue('A3', 'Nombre de sucursal');
$sheet->setCellValue('B3', 'Razón Social (Responsable Jurídico)');
$sheet->setCellValue('C3', 'Nombres del Responsable (Persona Física)');
$sheet->setCellValue('D3', 'Apellidos del Responsable (Persona Física)');

// Consulta para obtener los datos de las sucursales
$consulta_sucursales = "
    SELECT 
        s.nombreSucursal AS nombreSucursal,
        pj.razonSocial AS razonSocial,
        pf.nombres AS nombresResponsable,
        pf.apellidos AS apellidosResponsable
    FROM 
        tb_sucursal s
    LEFT JOIN 
        tb_personas_juridicas pj ON s.persona_juridica_id = pj.idPersonaJuridica
    LEFT JOIN 
        tb_personas_fisicas pf ON pj.persona_fisica_id = pf.idPersonaFisica
";

// Ejecutar la consulta
$resultado_sucursales = mysqli_query($conection, $consulta_sucursales);

// Agregar los datos de las sucursales a la hoja de cálculo
$row = 4; // Iniciar en la fila 4 para los datos
while ($row_sucursal = mysqli_fetch_assoc($resultado_sucursales)) {
    $sheet->setCellValue('A' . $row, $row_sucursal["nombreSucursal"]);
    $sheet->setCellValue('B' . $row, $row_sucursal["razonSocial"]);
    $sheet->setCellValue('C' . $row, $row_sucursal["nombresResponsable"]);
    $sheet->setCellValue('D' . $row, $row_sucursal["apellidosResponsable"]);
    $row++;
}

// Configuración de la cabecera para evitar el almacenamiento en caché del archivo
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"Sucursales_Exportacion_Fecha_{$fecha_actual}.xlsx\"");
header("Cache-Control: max-age=0");

// Crear el archivo Excel y enviarlo al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
