<?php
session_start();

include('../../modelos/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombrePais = trim($_POST['nombrePais']);

    if (empty($nombrePais)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'title' => 'Error',
            'text' => 'El nombre del país es obligatorio.'
        ];
        header("Location: frm_tb_paises.php");
        exit;
    }

    // Consultar si el país ya existe
    $query = "SELECT COUNT(*) FROM tb_paises WHERE nombrePais = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param('s', $nombrePais);
    $stmt->execute();
    $stmt->bind_result($paisExiste);
    $stmt->fetch();
    $stmt->close();

    if ($paisExiste > 0) {
        // Si el país ya existe, mostrar mensaje de error
        $_SESSION['message'] = [
            'type' => 'error',
            'title' => 'Error',
            'text' => 'Este país ya está registrado en la base de datos.',
            'confirmButtonColor' => '#d33'
        ];
        header("Location: frm_tb_paises.php");
        exit;
    }

    // Si el país no existe, insertar el nuevo país en la base de datos
    $queryInsert = "INSERT INTO tb_paises (nombrePais) VALUES (?)";
    $stmtInsert = $conection->prepare($queryInsert);
    $stmtInsert->bind_param('s', $nombrePais);

    // Ejecutar la inserción
    if ($stmtInsert->execute()) {
        // Mensaje de éxito
        $_SESSION['message'] = [
            'type' => 'success',
            'title' => 'Éxito',
            'text' => 'El país ha sido registrado correctamente.',
            'confirmButtonColor' => '#007bff'
        ];
        header("Location: frm_tb_paises.php");
        exit;
    } else {
        // Si ocurre un error durante la inserción, mostrar mensaje de error
        $_SESSION['message'] = [
            'type' => 'error',
            'title' => 'Error',
            'confirmButtonColor' => '#d33',
            'text' => 'Hubo un problema al registrar el país.'
        ];
        header("Location: frm_tb_paises.php");
        exit;
    }
}
