<?php

session_start();

require '../modelos/conexion.php';

if (isset($_SESSION['caja_id']) && isset($_SESSION['sucursal_id'])) {
    $caja_id = $_SESSION['caja_id'];
    $sucursal_id = $_SESSION['sucursal_id'];

    // Consulta para obtener solo la caja abierta
    $sqlCaja = "SELECT * FROM tb_caja WHERE idCaja = ? AND estado_caja_id = 40";

    // Consulta para obtener la sucursal activa
    $sqlSucursal = "SELECT * FROM tb_sucursal WHERE idSucursal = ?";

    if ($stmtCaja = $conection->prepare($sqlCaja)) {
        $stmtCaja->bind_param("i", $caja_id);
        $stmtCaja->execute();
        $resultadoCaja = $stmtCaja->get_result();

        if ($resultadoCaja->num_rows > 0) {
            $caja = $resultadoCaja->fetch_assoc();

            if ($stmtSucursal = $conection->prepare($sqlSucursal)) {
                $stmtSucursal->bind_param("i", $sucursal_id);
                $stmtSucursal->execute();
                $resultadoSucursal = $stmtSucursal->get_result();

                if ($resultadoSucursal->num_rows > 0) {
                    $sucursal = $resultadoSucursal->fetch_assoc(); 

                    // Mostrar los datos guardados en la sesión
                    echo "Datos de la sesión<br>";
                    echo "C&oacute;digo de caja " . $_SESSION['caja_id'] . "<br>";
                    echo "Nombre de caja " . $caja['nombreCaja'] . "<br>";
                    echo "Saldo actual en caja " . $caja['saldoActualCaja'] . "<br>";
                    echo "C&oacute;digo de sucursal " . $_SESSION['sucursal_id'] . "<br>";
                    echo "Nombre de sucursal " . $sucursal['nombreSucursal'];
                } else {
                    echo "No se encontró una sucursal activa con el número de identificación proporcionado.";
                }

                $stmtSucursal->close();
            } else {
                echo "Error al preparar la consulta para la sucursal: " . $conection->error;
            }
        } else {
            echo "No se encontró una caja abierta con el c&oacute;digo proporcionado.";
        }

        $stmtCaja->close();
    } else {
        echo "Error al preparar la consulta para la caja " . $conection->error;
    }
} else {
    echo "No se ha establecido el c&oacute;digo de la caja o la sucursal en la sesión.";
}

$conection->close();
