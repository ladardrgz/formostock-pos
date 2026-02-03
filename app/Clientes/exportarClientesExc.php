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
$sheet->setCellValue('A1', 'Lista de clientes (' . $fecha_hora_texto . ')');
$sheet->setCellValue('A3', 'Nombre completo');
$sheet->setCellValue('B3', 'Documento');
$sheet->setCellValue('C3', 'Fecha de nacimiento');
$sheet->setCellValue('D3', 'Sexo');
$sheet->setCellValue('E3', 'Información de contacto');
$sheet->setCellValue('F3', 'Dirección');

// Seleccionar todos los elementos de la tabla
$consulta_clientes = "
    SELECT  
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombre_completo, 
        tb_detalle_documento.valorDocumento AS documento,
        tb_personas_fisicas.fechaNacimiento AS fecha_nacimiento,
        tb_personas_fisicas.sexo AS sexo,
        tb_detalle_contacto.valorDetalleContacto AS contacto, 
        CONCAT(
            IFNULL(tb_domicilios_personas.valorDomicilio, ''), ', ',
            IFNULL(tb_barrios.nombreBarrio, ''), ', ',
            IFNULL(tb_localidades.nombreLocalidad, ''), ', ',
            IFNULL(tb_provincias.nombreProvincia, ''), ', ',
            IFNULL(tb_paises.nombrePais, '')
        ) AS direccion_completa
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

$resultado_clientes = mysqli_query($conection, $consulta_clientes);

// Agregar los datos a la hoja de cálculo
$row = 4;
while ($row_cliente = mysqli_fetch_assoc($resultado_clientes)) {
    $sheet->setCellValue('A' . $row, $row_cliente["nombre_completo"]);
    $sheet->setCellValue('B' . $row, $row_cliente["documento"]);
    $sheet->setCellValue('C' . $row, $row_cliente["fecha_nacimiento"]);
    $sheet->setCellValue('D' . $row, $row_cliente["sexo"]);
    $sheet->setCellValue('E' . $row, $row_cliente["contacto"]);
    $sheet->setCellValue('F' . $row, $row_cliente["direccion_completa"]);
    $row++;
}

// Configuración de la cabecera para evitar el almacenamiento en caché del archivo
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"Clientes_Exportacion_Fecha_{$fecha_actual}.xlsx\"");
header("Cache-Control: max-age=0");

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
