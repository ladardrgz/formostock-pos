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
$sheet->setCellValue('A1', 'Lista de usuarios (' . $fecha_hora_texto . ')');
$sheet->setCellValue('A3', 'Nombre de usuario');
$sheet->setCellValue('B3', 'Correo electrónico');
$sheet->setCellValue('C3', 'Estado de actividad');
$sheet->setCellValue('D3', 'Usuario');
$sheet->setCellValue('E3', 'Rol administrativo');

// Seleccionar todos los elementos de la tabla
$consulta_usuarios = "
    SELECT  
        usuarios.nombreCuentaUsuario AS nombreUsuario, 
        usuarios.emailUsuario AS emailUsuario,
        estados.nombreEstLog AS estado,
        CONCAT(personas.nombres, ' ', personas.apellidos) AS personaFisica,
        roles.nombreRol AS rol
    FROM 
        tb_usuarios usuarios
    INNER JOIN 
        tb_estados_logicos estados ON usuarios.estado_usuario_id = estados.idEstLog
    INNER JOIN 
        tb_personas_fisicas personas ON usuarios.persona_fisica_id = personas.idPersonaFisica
    INNER JOIN 
        tb_roles roles ON usuarios.rol_id = roles.idRol
";

$resultado_usuarios = mysqli_query($conection, $consulta_usuarios);

// Agregar los datos a la hoja de cálculo
$row = 4;
while ($row_usuario = mysqli_fetch_assoc($resultado_usuarios)) {
    $sheet->setCellValue('A' . $row, $row_usuario["nombreUsuario"]);
    $sheet->setCellValue('B' . $row, $row_usuario["emailUsuario"]);
    $sheet->setCellValue('C' . $row, $row_usuario["estado"]);
    $sheet->setCellValue('D' . $row, $row_usuario["personaFisica"]);
    $sheet->setCellValue('E' . $row, $row_usuario["rol"]);
    $row++;
}

// Configuración de la cabecera para evitar el almacenamiento en caché del archivo
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"Usuarios_Exportacion_Fecha_{$fecha_actual}.xlsx\"");
header("Cache-Control: max-age=0");

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
