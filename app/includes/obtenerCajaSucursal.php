<?php
// Iniciar la sesión y conectar con la base de datos
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'modelos/conexion.php'; // Asegúrate de que la ruta sea correcta

// Inicializar variables
$cajaInfo = "Caja no encontrada o no activa";
$sucursalInfo = "Sucursal no encontrada";

// Verificar si las variables de sesión están establecidas
if (isset($_SESSION['caja_id']) && isset($_SESSION['sucursal_id'])) {
    $caja_id = $_SESSION['caja_id'];
    $sucursal_id = $_SESSION['sucursal_id'];

    // Consulta para obtener la información de la caja activa
    $sqlCaja = "SELECT nombreCaja, saldoActualCaja FROM tb_caja WHERE idCaja = ? AND estado_caja_id = 40";

    // Consulta para obtener la información de la sucursal activa
    $sqlSucursal = "SELECT nombreSucursal FROM tb_sucursal WHERE idSucursal = ?";

    // Consultar la información de la caja
    if ($stmtCaja = $conection->prepare($sqlCaja)) {
        $stmtCaja->bind_param("i", $caja_id);
        $stmtCaja->execute();
        $resultadoCaja = $stmtCaja->get_result();

        if ($resultadoCaja->num_rows > 0) {
            $caja = $resultadoCaja->fetch_assoc();
           // Modificar el formato de la caja para que el signo de pesos esté al final
$cajaInfo = "Usted está operando en: " . $caja['nombreCaja'] . " - Saldo " . number_format($caja['saldoActualCaja'], 2) . " $";

        }
        $stmtCaja->close();
    }

    // Consultar la información de la sucursal
    if ($stmtSucursal = $conection->prepare($sqlSucursal)) {
        $stmtSucursal->bind_param("i", $sucursal_id);
        $stmtSucursal->execute();
        $resultadoSucursal = $stmtSucursal->get_result();

        if ($resultadoSucursal->num_rows > 0) {
            $sucursal = $resultadoSucursal->fetch_assoc();
            $sucursalInfo = "Sucursal " . $sucursal['nombreSucursal'];
        }
        $stmtSucursal->close();
    }
}

// Devolver la información en un array para que pueda ser utilizada
return [
    'cajaInfo' => $cajaInfo,
    'sucursalInfo' => $sucursalInfo
];
?>
