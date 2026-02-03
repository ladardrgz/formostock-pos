<?php
session_start();
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    echo "Error en la conexión: " . mysqli_connect_error();
    exit();
}

// Verificar si se ha enviado el formulario para agregar un tipo de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreTipoContacto'])) {
    $nombreTipoContacto = mysqli_real_escape_string($conection, $_POST['nombreTipoContacto']);

    // Verificar si el nombre ya existe
    $check_query = "SELECT COUNT(*) as count FROM tb_tipo_contacto WHERE nombreTipoContacto = ?";
    $stmtCheck = $conection->prepare($check_query);
    if ($stmtCheck === false) {
        echo "Error al preparar la consulta: " . $conection->error;
        exit();
    }

    $stmtCheck->bind_param('s', $nombreTipoContacto);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $row = $resultCheck->fetch_assoc();

    if ($row['count'] > 0) {
        // Si el nombre ya existe, redirigir con mensaje de error
        header('Location: frm_nuevo_tipoContacto.php?message=nombre_existente');
        exit();
    }

    // Iniciar una transacción
    mysqli_begin_transaction($conection);

    try {
        // Insertar en la tabla tb_tipo_contacto
        $query_tipo_contacto = "INSERT INTO tb_tipo_contacto (nombreTipoContacto) VALUES (?)";
        $stmtInsert = $conection->prepare($query_tipo_contacto);
        if ($stmtInsert === false) {
            throw new Exception("Error al preparar la consulta de inserción.");
        }

        $stmtInsert->bind_param('s', $nombreTipoContacto);

        if ($stmtInsert->execute()) {
            // Confirmar la transacción
            mysqli_commit($conection);
            header('Location: tb_tipoContacto.php?message=registrado');
            exit();
        } else {
            throw new Exception("Error al ejecutar la inserción: " . $stmtInsert->error);
        }
    } catch (Exception $e) {
        mysqli_rollback($conection);
        header('Location: frm_nuevo_tipoContacto.php?message=error');
        exit();
    } finally {
        mysqli_close($conection);
    }
}
?>
