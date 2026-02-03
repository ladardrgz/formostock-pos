<?php
require_once "../../modelos/conexion.php";
session_start();

$response = array('success' => false, 'message' => '');

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que los campos necesarios no estén vacíos
    if (!empty($_POST['nombreprovincia']) && !empty($_POST['pais_id'])) {
        $nombreProvincia = trim($_POST['nombreprovincia']);
        $paisId = (int)$_POST['pais_id'];  // Aseguramos que el ID del país sea un número entero

        // Validar que el nombre de la provincia no contenga caracteres especiales o sea demasiado corto
        if (strlen($nombreProvincia) < 5 || strlen($nombreProvincia) > 50) {
            $response['message'] = "El nombre de la provincia debe tener entre 5 y 50 caracteres.";
        } else {
            // Verificar si el nombre de la provincia ya existe en el país seleccionado
            $queryCheckProvincia = "SELECT COUNT(*) FROM tb_provincias WHERE nombreProvincia = ? AND pais_id = ?";
            $stmtCheck = $conection->prepare($queryCheckProvincia);
            if ($stmtCheck === false) {
                $response['message'] = "Error en la preparación de la consulta para verificar la provincia.";
            } else {
                $stmtCheck->bind_param('si', $nombreProvincia, $paisId);
                $stmtCheck->execute();
                $stmtCheck->bind_result($count);
                $stmtCheck->fetch();
                $stmtCheck->close();

                if ($count > 0) {
                    // Si el nombre de la provincia ya existe, devolver un mensaje de error
                    $response['message'] = "Ya existe una provincia con ese nombre en el país seleccionado.";
                } else {
                    // Preparar y ejecutar la consulta para insertar la nueva provincia
                    $queryInsertProvincia = "INSERT INTO tb_provincias (nombreProvincia, pais_id) VALUES (?, ?)";
                    $stmtInsert = $conection->prepare($queryInsertProvincia);
                    if ($stmtInsert === false) {
                        $response['message'] = "Error en la preparación de la consulta para insertar la provincia.";
                    } else {
                        $stmtInsert->bind_param('si', $nombreProvincia, $paisId);

                        if ($stmtInsert->execute()) {
                            $response['success'] = true;
                            $response['message'] = "Provincia registrada correctamente.";
                        } else {
                            $response['message'] = "Error al registrar la provincia: " . $stmtInsert->error;
                        }

                        $stmtInsert->close();
                    }
                }
            }
        }
    } else {
        $response['message'] = "Por favor ingresa el nombre de la provincia y selecciona el país.";
    }
}

// Cerrar la conexión a la base de datos
$conection->close();

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
