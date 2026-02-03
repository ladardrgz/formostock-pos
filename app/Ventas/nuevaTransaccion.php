<?php
session_start();

include '../modelos/conexion.php';
include 'obtCatVen.php';
include 'obtMarVen.php';
include 'obtImpVen.php';
include 'obtForPagVen.php';
include 'obtProVen.php';

$categorias = obtenerCategorias($conection);
$marcas = obtenerMarcas($conection);

$buscar = isset($_GET['search']) ? $_GET['search'] : '';
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$marca_id = isset($_GET['marca']) ? (int)$_GET['marca'] : null;
$filtrar_precio = isset($_GET['filter_price']) ? $_GET['filter_price'] : '';
$pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite = 5;

$resultado = obtenerProductos($conection, $buscar, $categoria_id, $marca_id, $filtrar_precio, $pagina, $limite);
$productos = $resultado['productos'];
$total_paginas = $resultado['total_paginas'];
$pagina_actual = $resultado['pagina_actual'];

// Obtener el sucursal_id desde la sesión
$sucursal_id = $_SESSION['sucursal_id']; // Asume que ya está en la sesión

// Consulta SQL para obtener el stock de los productos de esa sucursal
$query_stock = "SELECT p.idProducto, p.descripcionProducto, i.stockSucursal 
                FROM tb_productos p
                JOIN tb_inventario_sucursal i ON p.idProducto = i.producto_id
                WHERE i.sucursal_id = $sucursal_id";

$result_stock = mysqli_query($conection, $query_stock);

// Crear un array para almacenar los stocks de los productos
$stocks = [];
if ($result_stock) {
    while ($row = mysqli_fetch_assoc($result_stock)) {
        $stocks[$row['idProducto']] = $row['stockSucursal'];
    }
}

mysqli_close($conection);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva venta</title>
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleNuevaTransaccion.css">
</head>

<body>
    <?php include 'nav_ventas.php'; ?>

    <div class="container">
        <form id="venta-form" method="GET" action="" class="buscador-producto">
            <div class="buscador">
                <input type="text" name="search" value="<?php echo htmlspecialchars($buscar); ?>" placeholder="Buscar producto..." class="buscador-producto-input">
                <select name="categoria" class="buscador-filtro-por-categoria">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['idCategoriaProducto']; ?>" <?php echo ($categoria_id == $categoria['idCategoriaProducto']) ? 'selected' : ''; ?>>
                            <?php echo $categoria['nombreCategoriaProducto']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="marca" class="buscador-filtro-por-marcas">
                    <option value="">Todas las marcas</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['idMarcaProducto']; ?>" <?php echo ($marca_id == $marca['idMarcaProducto']) ? 'selected' : ''; ?>>
                            <?php echo $marca['nombreMarcaProducto']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="filter_price" class="buscador-filtro-por-precio">
                    <option value="">Seleccione un rango</option>
                    <option value="1000" <?php echo ($filtrar_precio == '1000') ? 'selected' : ''; ?>>Hasta $1000</option>
                    <option value="5000" <?php echo ($filtrar_precio == '5000') ? 'selected' : ''; ?>>Hasta $5000</option>
                    <option value="10000" <?php echo ($filtrar_precio == '10000') ? 'selected' : ''; ?>>Hasta $10000</option>
                </select>
                <button type="submit" class="boton-realizar-busqueda-producto">
                    <img src="../assets/img/icono-busqueda.png" alt="Buscar" class="icono-busqueda">
                    Realizar búsqueda
                </button>
                <button type="button" class="boton-limpiar-busquedas" id="limpiar-filtros">
                    <img src="../assets/img/icono-limpieza.png" alt="Limpiar filtros" class="icono-busqueda">
                    Limpiar filtros
                </button>
                <script>
                    document.getElementById('limpiar-filtros').addEventListener('click', function() {
                        const form = document.getElementById('venta-form');
                        form.reset();

                        const categoriaSelect = form.querySelector('.buscador-filtro-por-categoria');
                        const marcaSelect = form.querySelector('.buscador-filtro-por-marcas');
                        const precioSelect = form.querySelector('.buscador-filtro-por-precio');
                        categoriaSelect.value = '';
                        marcaSelect.value = '';
                        precioSelect.value = '';
                    });
                </script>
            </div>
        </form>

        <div class="productos">
            <?php if (!empty($productos)): ?>
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>Código de barras</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?php echo $producto['codigoBarrasProducto']; ?></td>
            <td><?php echo $producto['descripcionProducto']; ?></td>
            <td><?php echo $producto['precioProducto']; ?></td>
            <td>
                <?php
                    // Obtener el stock disponible de este producto en la sucursal
                    $stockDisponible = isset($stocks[$producto['idProducto']]) ? $stocks[$producto['idProducto']] : 0;
                ?>
                <button class="agregar-al-carrito" 
                        data-id="<?php echo $producto['idProducto']; ?>" 
                        data-descripcion="<?php echo $producto['descripcionProducto']; ?>" 
                        data-precio="<?php echo $producto['precioProducto']; ?>"
                        data-stock="<?php echo $stockDisponible; ?>"> <!-- Agregar el stock disponible a los atributos -->
                    <img src="../assets/img/anadir-al-carrito.png" alt="Agregar" class="icono-agregar">
                    <span>Agregar al carrito</span>
                </button>
                <span class="stock-disponible">Stock disponible: <?php echo $stockDisponible; ?></span> <!-- Mostrar el stock disponible -->
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </div>

        <?php include 'paginador.php'; ?>

        <fieldset class="fieldset-ticket-de-venta">
            <legend>
                <img src="../assets/img/imgCliente.png" alt="Icono" class="icono-ticket"> Ticket de venta
            </legend>
            <label for="buscador">Buscar cliente:</label>
            <input type="text" id="buscador" class="buscador-input-cliente" placeholder="Buscar cliente..." class="buscador-input" oninput="filtrarClientes()">

            <input type="text" id="cliente_id" name="cliente_id" value="" hidden>
            <ul id="resultado-clientes"></ul>

            <label for="cliente_seleccionado">Cliente seleccionado:</label>
            <input type="text" id="cliente_seleccionado" name="cliente_seleccionado" value="" readonly class="cliente-seleccionado">
        </fieldset>

        <div id="carrito" class="carrito">
            <fieldset class="fieldset-carrito">
                <legend>
                    <img src="../assets/img/cart-icon.png" alt="Icono carrito" class="icono-carrito">
                    Carrito de compra
                </legend>
            </fieldset>

            <table id="tabla-carrito" class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se agregarán los productos del carrito -->
                </tbody>
            </table>
            <div class="valores-resumen">
                <p class="resumen-item">
                    <span id="subtotal-venta" class="subtotal-venta">Subtotal: $0</span>
                </p>
                <p class="resumen-item">
                    <span id="impuesto" class="impuesto">I.V.A 21%</span>
                </p>
                <p class="resumen-item">
                    <span id="total-venta" class="total-venta">Total: $0</span>
                </p>
            </div>
        </div>

        <fieldset class="fieldset-forma-de-pago">
            <legend>
                <img src="../assets/img/metodo-de-pago.png" alt="Icono" class="icono-ticket"/> Forma de pago
            </legend>
            <select id="forma_pago" name="formaPago" required class="select-de-forma-pago">
                <option value="">Seleccione la forma de pago</option>
                <?php foreach ($formas_pago as $forma): ?>
                    <option value="<?php echo $forma['idFormaPago']; ?>"><?php echo $forma['nombreFormaPago']; ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="hidden" id="caja_id" name="caja_id" value="<?php echo isset($_SESSION['caja_id']) ? $_SESSION['caja_id'] : ''; ?>">
        <input type="hidden" id="sucursal_id" name="sucursal_id" value="<?php echo isset($_SESSION['sucursal_id']) ? $_SESSION['sucursal_id'] : ''; ?>">

        <button id="finalizar-venta" class="boton finalizar-venta" onclick="finalizarVenta()">Finalizar venta</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="funcionIniciarFac.js"></script>
    <script src="clientesAutoCompletar.js"></script>
    <script src="gestionCarrito.js"></script>
    <script src="finalizarVenta.js"></script>
    <script src="ButtonOnTheRight.js"></script>
</body>

</html>