<?php
session_start();
include_once('../modelos/conexion.php');
require 'vendor/autoload.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$dia = date('d');
$mes = $meses[date('n')];
$anio = date('Y');
$hora = date('H:i');
$fecha_hora_texto = "{$dia} de {$mes} de {$anio} a las {$hora}";
$fecha_actual = date('d-m-Y_H-i');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Listado de Proveedores (' . $fecha_hora_texto . ')');
$sheet->setCellValue('A3', 'Número de Cliente');
$sheet->setCellValue('B3', 'Documento del Cliente');
$sheet->setCellValue('C3', 'Nombre Completo');
$sheet->setCellValue('D3', 'Fecha de Nacimiento');
$sheet->setCellValue('E3', 'Sexo');
$sheet->setCellValue('F3', 'Información de Contacto');
$sheet->setCellValue('G3', 'Domicilio');

$consulta_proveedores = "
    SELECT 
        tb_clientes.idCliente AS 'Número de cliente',
        tb_detalle_documento.valorDocumento AS 'Documento del cliente',
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS 'Nombre Completo',
        tb_personas_fisicas.fechaNacimiento AS 'Fecha de nacimiento',
        tb_personas_fisicas.sexo AS 'Sexo',
        tb_detalle_contacto.valorDetalleContacto AS 'Información de contacto',
        CONCAT(
            IFNULL(tb_domicilios_personas.valorDomicilio, ''), ', ',
            IFNULL(tb_barrios.nombreBarrio, ''), ', ',
            IFNULL(tb_localidades.nombreLocalidad, ''), ', ',
            IFNULL(tb_provincias.nombreProvincia, ''), ', ',
            IFNULL(tb_paises.nombrePais, '')
        ) AS 'Domicilio'
    FROM 
        tb_clientes
    INNER JOIN 
        tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN 
        tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    LEFT JOIN 
        tb_detalle_contacto ON tb_personas_fisicas.detalle_contacto_id = tb_detalle_contacto.idDetalleContacto
    LEFT JOIN 
        tb_domicilios_personas ON tb_personas_fisicas.idPersonaFisica = tb_domicilios_personas.persona_fisica_id
    LEFT JOIN 
        tb_domicilios ON tb_domicilios_personas.domicilio_id = tb_domicilios.idDomicilio
    LEFT JOIN 
        tb_barrios ON tb_domicilios.barrio_id = tb_barrios.idBarrio
    LEFT JOIN 
        tb_localidades ON tb_barrios.localidad_id = tb_localidades.idLocalidad
    LEFT JOIN 
        tb_provincias ON tb_localidades.provincia_id = tb_provincias.idProvincia
    LEFT JOIN 
        tb_paises ON tb_provincias.pais_id = tb_paises.idPais
    WHERE 
        tb_personas_fisicas.estado_persona_id = 1
";

$resultado_proveedores = mysqli_query($conection, $consulta_proveedores);

$row = 4;
while ($row_proveedor = mysqli_fetch_assoc($resultado_proveedores)) {
    $sheet->setCellValue('A' . $row, $row_proveedor["Número de cliente"]);
    $sheet->setCellValue('B' . $row, $row_proveedor["Documento del cliente"]);
    $sheet->setCellValue('C' . $row, $row_proveedor["Nombre Completo"]);
    $sheet->setCellValue('D' . $row, $row_proveedor["Fecha de nacimiento"]);
    $sheet->setCellValue('E' . $row, $row_proveedor["Sexo"]);
    $sheet->setCellValue('F' . $row, $row_proveedor["Información de contacto"]);
    $sheet->setCellValue('G' . $row, $row_proveedor["Domicilio"]);
    $row++;
}

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"Proveedores_Exportacion_Fecha_{$fecha_actual}.xlsx\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
