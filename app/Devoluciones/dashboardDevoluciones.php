<?php
session_start();
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php';
include_once 'datosTablaDevoluciones.php';
include_once 'obtenerTiposDeNota.php';

$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search = mysqli_real_escape_string($conection, $search);

// Consulta para contar el número total de devoluciones que coinciden con la búsqueda (motivo de devolución o factura)
$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_devoluciones
    LEFT JOIN tb_factura_cabecera ON tb_devoluciones.factura_cabecera_id = tb_factura_cabecera.idFaCab
    WHERE tb_devoluciones.motivoDevolucion LIKE '%$search%' 
    OR tb_factura_cabecera.idFaCab LIKE '%$search%'
";

$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

// Crear una instancia de Paginador para manejar la paginación
$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Devoluciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleDashboard.css">
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
    <?php include 'nav_devoluciones.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Devoluciones</h1>

        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="dashboardDevoluciones.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar devoluciones por factura o motivo de devolución..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>N° de factura</th>
                    <th>Motivo de la devolución</th>
                    <th>Cantidad devuelta</th>
                    <th>Fecha de devolución</th>
                    <th>Condiciones de entrega</th>
                    <th>Tipo de devolución</th>
                    <th>Tipo de movimiento contable</th>
                    <th>Estado de la nota</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $inicio = ($pagina_actual - 1) * $registros_por_pagina;
                $devoluciones = obtenerDevoluciones($inicio, $registros_por_pagina, $search);

                foreach ($devoluciones as $devolucion) {
                    // Obtener los datos necesarios de la devolución
                    $facturaId = isset($devolucion['factura_cabecera_id']) ? $devolucion['factura_cabecera_id'] : '';
                    $motivoDevolucion = isset($devolucion['motivoDevolucion']) ? $devolucion['motivoDevolucion'] : '';
                    $cantidadDevolucion = isset($devolucion['cantidadDevolucion']) ? $devolucion['cantidadDevolucion'] : '';
                    $fechaDevolucion = isset($devolucion['fechaDevolucion']) ? $devolucion['fechaDevolucion'] : '';
                    $condicionesEntrega = isset($devolucion['condicionesDeEntrega']) ? $devolucion['condicionesDeEntrega'] : '';
                    $tipoDevolucion = isset($devolucion['tipoDevolucion']) ? $devolucion['tipoDevolucion'] : ''; // Nuevo campo
                    $tipoNota = isset($devolucion['tipoNota']) ? $devolucion['tipoNota'] : '';

                    echo "<tr>";
                    echo "<td>{$facturaId}</td>";
                    echo "<td>{$motivoDevolucion}</td>";
                    echo "<td>{$cantidadDevolucion}</td>";
                    echo "<td>{$fechaDevolucion}</td>";
                    echo "<td>{$condicionesEntrega}</td>";
                    echo "<td>{$tipoDevolucion}</td>";
                    echo "<td>{$tipoNota}</td>";
                    echo "<td>{$devolucion['estadoNota']}</td>";

                    echo "<td>
                    <a href='verNotasPersonas.php?id={$devolucion['idDevolucion']}' class='btn-img view-button' data-id='{$devolucion['idDevolucion']}'>
                        <img src='../assets/img/verDev.png' alt='Ver detalle'>
                    </a>

                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Mostrar la paginación -->
        <nav aria-label="Page navigation">
            <?php echo $paginador->mostrar_paginacion(); ?>
        </nav>
    </div>
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/gestorEventosDevoluciones.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['mensaje'])) {
                $tipo_mensaje = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : 'success';
                echo "Swal.fire({
                title: '" . ucfirst($tipo_mensaje) . "',
                text: '" . $_SESSION['mensaje'] . "',
                icon: '" . $tipo_mensaje . "',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            });";
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']);
            }
            ?>
        });
    </script>

</body>

</html>