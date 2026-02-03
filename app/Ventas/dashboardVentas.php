<?php
session_start();
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php';
include_once 'mostrarTablaVentas.php';
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search = mysqli_real_escape_string($conection, $search);

$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_factura_cabecera
    LEFT JOIN tb_clientes ON tb_factura_cabecera.cliente_id = tb_clientes.idCliente
    LEFT JOIN tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    WHERE tb_personas_fisicas.nombres LIKE '%$search%' 
    OR tb_personas_fisicas.apellidos LIKE '%$search%'
    OR tb_detalle_documento.valorDocumento LIKE '%$search%'
";

$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        .btn-img img {
            width: 35px;
            height: 35px;
        }
    </style>
</head>

<body>
    <?php include 'nav_ventas.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Ventas</h1>

        <form method="GET" action="dashboardVentas.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar facturas por cliente o documento..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
            <div class="form-group">
                <label for="num_registros">Mostrar</label>
                <select id="num_registros" name="num_registros" class="form-select custom-select" onchange="this.form.submit()" style="width: 70px; height: 35px;">
                    <option value="5" <?php echo $registros_por_pagina == 5 ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo $registros_por_pagina == 20 ? 'selected' : ''; ?>>20</option>
                    <option value="30" <?php echo $registros_por_pagina == 30 ? 'selected' : ''; ?>>30</option>
                    <option value="50" <?php echo $registros_por_pagina == 50 ? 'selected' : ''; ?>>50</option>
                </select>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th>Número de documento</th>
                    <th>Fecha de emisión</th>
                    <th>Fecha de vencimiento</th>
                    <th>Monto total</th>
                    <th>Forma de pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Se calculan los índices para la paginación
                $inicio = ($pagina_actual - 1) * $registros_por_pagina;
                // Obtener las facturas desde la base de datos
                $facturas = obtenerFacturas($inicio, $registros_por_pagina, $search);

                // Verificar si hay facturas
                if (!empty($facturas)) {
                    foreach ($facturas as $factura) {
                        // Validación y asignación de valores
                        $nombreCompleto = !empty($factura['nombreCompletoCliente']) ? htmlspecialchars($factura['nombreCompletoCliente']) : 'No disponible';
                        $numeroDocumento = !empty($factura['numeroDocumento']) ? htmlspecialchars($factura['numeroDocumento']) : 'No disponible';
                        $fechaOriginal = $factura['fechaDeVencimientoFaCab'];
                        $fechaFormateada = !empty($fechaOriginal) ? date("d/m/Y", strtotime($fechaOriginal)) : 'No especificada';
                        $fechaOriginalEmision = $factura['fechaDeEmisionFaCab'];
                        $fechaFormateadaEmision = !empty($fechaOriginalEmision) ? date("d/m/Y", strtotime($fechaOriginalEmision)) : 'No especificada';
                        $montoTotal = number_format($factura['montoTotalFaCab'], 2, '.', ',') . '$';
                        $nombreFormaPago = !empty($factura['nombreFormaPago']) ? htmlspecialchars($factura['nombreFormaPago']) : 'No disponible';
                        $nombreEstado = !empty($factura['nombreEstLog']) ? htmlspecialchars($factura['nombreEstLog']) : 'No disponible';
                        $idFactura = $factura['idFaCab'];

                        $ubicacionDocumento = "../uploads/documentos/factura_{$idFactura}_*";
                        $archivos = glob($ubicacionDocumento);

                        echo "<tr>";
                        echo "<td>{$nombreCompleto}</td>";
                        echo "<td>{$numeroDocumento}</td>";
                        echo "<td>{$fechaFormateadaEmision}</td>";
                        echo "<td>{$fechaFormateada}</td>";
                        echo "<td>{$montoTotal}</td>";
                        echo "<td>{$nombreFormaPago}</td>";
                        echo "<td>{$nombreEstado}</td>";

                        echo "<td>
                <button type='button' class='btn-img view-button' data-id='{$idFactura}'>
                    <img src='../assets/img/verFactura.png' alt='Ver factura'  style='cursor: pointer; width: 40px; height: 48px;'>
                </button>
                <button type='button' class='btn-img cancel-button' data-id='{$idFactura}'>
                    <img src='../assets/img/anularFactura.png' alt='Cancelar factura' style='cursor: pointer; width: 50px; height: 48px;'>
                </button>
            </td>";
                        echo "<td>";
                        if (empty($archivos)) {
                            echo "<form action='cargar_documento.php' method='POST' enctype='multipart/form-data' style='display: inline;'>
                    <input type='file' name='documento' id='fileInput_{$idFactura}' class='d-none' accept='.pdf,.doc,.docx,.txt' onchange='this.form.submit()'>
                    <input type='hidden' name='idFactura' value='{$idFactura}'>
                    <img src='../assets/img/subirArchivo.png' alt='Cargar documento' style='cursor: pointer; width: 35px;' onclick='confirmarCarga({$idFactura});'>
                </form>";
                        } else {
                            echo "<img src='../assets/img/archivoGuardado.png' alt='Ver documento' style='cursor: pointer; width: 35px;' 
                    onclick='confirmarDescarga(\"{$archivos[0]}\")'>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No se encontraron facturas para mostrar.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <?php echo $paginador->mostrar_paginacion(); ?>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="ButtonOnTheRight.js"></script>
    <script src="accionesBotonesVentas.js"></script>

    <script>
        <?php if (isset($_SESSION['mensaje'])): ?>
            Swal.fire({
                title: '<?= ucfirst(isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : 'success') ?>',
                text: '<?= $_SESSION['mensaje'] ?>',
                icon: '<?= isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : 'success' ?>',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            });
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>
    </script>