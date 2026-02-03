<?php
session_start();
include '../modelos/conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreCaja = $_POST['nombreCaja'];
    $saldoInicialCaja = $_POST['saldoInicialCaja'];
    $saldoActualCaja = $_POST['saldoActualCaja'];
    $fechaAperturaCaja = $_POST['fechaAperturaCaja'];
    $fechaCierreCaja = !empty($_POST['fechaCierreCaja']) ? $_POST['fechaCierreCaja'] : null;
    $estadoCaja = $_POST['estado_caja_id'];
    $montoArqueoCaja = !empty($_POST['montoArqueoCaja']) ? $_POST['montoArqueoCaja'] : null;

    // Validación de campos obligatorios
    if (!empty($nombreCaja) && isset($saldoInicialCaja) && isset($saldoActualCaja) && !empty($fechaAperturaCaja)) {
        // Insertar los datos en la tabla `tb_caja`
        $sql = "INSERT INTO tb_caja (nombreCaja, saldoInicialCaja, saldoActualCaja, fechaAperturaCaja, fechaCierreCaja, estado_caja_id, montoArqueoCaja) 
                VALUES ('$nombreCaja', '$saldoInicialCaja', '$saldoActualCaja', '$fechaAperturaCaja', '$fechaCierreCaja', '$estadoCaja', '$montoArqueoCaja')";

        if (mysqli_query($conection, $sql)) {
            // Redirigir con éxito
            header("Location: CrearCaja.php?status=success");
            exit();
        } else {
            // Redirigir con error
            header("Location: CrearCaja.php?status=error");
            exit();
        }
    } else {
        // Redirigir si faltan campos
        header("Location: CrearCaja.php?status=error");
        exit();
    }
}

// Cerrar la conexión
mysqli_close($conection);
?>
