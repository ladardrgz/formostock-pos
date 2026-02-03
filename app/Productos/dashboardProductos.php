<?php
session_start();

include_once '../modelos/conexion.php';
include_once '../Controladores/Paginador.php';
include_once 'fetch_impuestos.php';
include_once 'listarProductos.php';

if (!isset($_SESSION['sucursal_id'])) {
    header("Location: guardarSucursalSession.php");
    exit();
}

$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search = mysqli_real_escape_string($conection, $search);
$sucursal_id = $_SESSION['sucursal_id'];

$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_productos
    LEFT JOIN tb_detalle_impuestos ON tb_productos.impuesto_id = tb_detalle_impuestos.idDetalleImpuesto
    LEFT JOIN tb_categorias_productos ON tb_productos.categoria_id = tb_categorias_productos.idCategoriaProducto
    LEFT JOIN tb_marcas_productos ON tb_productos.marca_id = tb_marcas_productos.idMarcaProducto
    LEFT JOIN tb_estados_logicos ON tb_productos.estado_producto_id = tb_estados_logicos.idEstLog
    LEFT JOIN tb_inventario_sucursal ON tb_productos.idProducto = tb_inventario_sucursal.producto_id AND tb_inventario_sucursal.sucursal_id = $sucursal_id
    WHERE tb_productos.descripcionProducto LIKE '%$search%'
    OR tb_categorias_productos.nombreCategoriaProducto LIKE '%$search%'
    OR tb_marcas_productos.nombreMarcaProducto LIKE '%$search%'
";

$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);

$impuestoClass = new Impuesto($conection);
$impuestos = $impuestoClass->obtenerImpuestos();
$impuestosMap = [];
foreach ($impuestos as $impuesto) {
    $impuestosMap[$impuesto['idDetalleImpuesto']] = $impuesto['nombreImpuesto'] . ' - ' . $impuesto['valorDetalleImpuesto'];
}

$inicio = ($pagina_actual - 1) * $registros_por_pagina;
$productos = obtenerProductos($inicio, $registros_por_pagina, $search);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>

<body>
    <?php include 'nav_productos.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Productos</h1>

        <form method="GET" action="dashboardProductos.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar producto por descripción, categorías y marcas..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>Código de barras</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($productos as $producto) {
                    echo "<tr>";
                    echo "<td>{$producto['codigoBarrasProducto']}</td>";
                    echo "<td>{$producto['descripcionProducto']}</td>";
                    echo "<td>{$producto['precioProducto']}</td>";
                    $cantidad = $producto['stockSucursal'] ?? 0;
                    echo "<td>{$cantidad}</td>";
                    echo "<td>{$producto['nombreEstLog']}</td>";
                    echo "<td>
            <a href='modificarProducto.php?id={$producto['idProducto']}' class='btn-img edit-button'>
                    <img src='../assets/img/boton-editar.ico' alt='Editar'>
                </a>
                <button type='button' class='btn-img delete-button' data-id='{$producto['idProducto']}'>
                    <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
                </button>
                <a href='detallesProducto.php?id={$producto['idProducto']}' class='btn-img details-button'>
                    <img src='../assets/img/boton-detalles.ico' alt='Ver más'>
                </a>
                <!-- Botón para aumentar stock -->
                <button type='button' class='btn-img increase-stock-button' data-id='{$producto['idProducto']}'>
                    <img src='../assets/img/cajas.png' alt='Aumentar stock'>
                </button>
                    </td>";
                    echo "</tr>";
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
    <script src="gestorEventosProductos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "Swal.fire({
                    title: 'Éxito',
                    text: '" . $_SESSION['mensaje'] . "',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                });";
                unset($_SESSION['mensaje']);
            }
            ?>
        });
    </script>
</body>

</html>