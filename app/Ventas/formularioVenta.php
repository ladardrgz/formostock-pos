<?php
session_start();
// Incluyo archivo de conexión.
include '../modelos/conexion.php';
// Incluyo archivo de consulta para obtener categorías de productos.
include 'obtCatVen.php';
// Incluyo archivo de consulta para obtener marcas de productos.
include 'obtMarVen.php';
// Incluyo archivo de consulta de impuestos.
include 'obtImpVen.php';
// Incluyo archivo de consulta de formas de pago.
include 'obtForPagVen.php';
// Incluyo archivo de consulta para obtener productos.
include 'obtProVen.php';

// Inicializo variables de categorías, marcas, y formas de pago.
$categorias = obtenerCategorias($conection);
$marcas = obtenerMarcas($conection);

// Se verifica si los parámetros de búsqueda están presentes en la URL.
$buscar = isset($_GET['search']) ? $_GET['search'] : '';
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$marca_id = isset($_GET['marca']) ? (int)$_GET['marca'] : null;
$filtrar_precio = isset($_GET['filter_price']) ? $_GET['filter_price'] : '';
$pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite = 5;

// Llamar a la función para obtener los productos y la información de la paginación.
$resultado = obtenerProductos($conection, $buscar, $categoria_id, $marca_id, $filtrar_precio, $pagina, $limite);

$productos = $resultado['productos'];
$total_paginas = $resultado['total_paginas'];
$pagina_actual = $resultado['pagina_actual'];

mysqli_close($conection);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva venta</title>
    <link rel="stylesheet" href="style_Venta.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include 'nav_ventas.php'; ?>
    <div class="container">
        <!-- Buscador -->
        <form id="venta-form" method="GET" action="" class="buscador-form">
            <div class="buscador">
                <input type="text" name="search" value="<?php echo htmlspecialchars($buscar); ?>" placeholder="Buscar producto..." class="buscador-input">
                <select name="categoria" class="buscador-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['idCategoriaProducto']; ?>" <?php if ($categoria_id == $categoria['idCategoriaProducto']) echo 'selected'; ?>>
                            <?php echo $categoria['nombreCategoriaProducto']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="marca" class="buscador-select">
                    <option value="">Todas las marcas</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo $marca['idMarcaProducto']; ?>" <?php if ($marca_id == $marca['idMarcaProducto']) echo 'selected'; ?>>
                            <?php echo $marca['nombreMarcaProducto']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="filter_price" class="buscador-select">
                    <option value="">Seleccione un rango</option>
                    <option value="1000" <?php if ($filtrar_precio == '1000') echo 'selected'; ?>>Hasta $1000</option>
                    <option value="5000" <?php if ($filtrar_precio == '5000') echo 'selected'; ?>>Hasta $5000</option>
                    <option value="10000" <?php if ($filtrar_precio == '10000') echo 'selected'; ?>>Hasta $10000</option>
                </select>
                <button type="submit" class="buscador-boton">Realizar búsqueda</button>
            </div>
        </form>

        <!-- Tabla de productos -->
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
                                    <button class="agregar-al-carrito" data-id="<?php echo $producto['idProducto']; ?>" data-descripcion="<?php echo $producto['descripcionProducto']; ?>" data-precio="<?php echo $producto['precioProducto']; ?>">
                                        <img src="../assets/img/anadir-al-carrito.png" alt="Agregar" class="icono-agregar">
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </div>

        <!-- Incluir el paginador -->
        <?php include 'paginador.php'; ?>

        <!-- Ticket venta -->
        <fieldset class="fieldset">
            <legend>
                <img src="../assets/img/imgCliente.png" alt="Icono" class="icono-ticket" /> Ticket de venta
            </legend>
            <label for="buscador" class="label-buscador">Buscar cliente:</label>
            <input type="text" id="buscador" placeholder="Buscar cliente..." class="buscador-input" oninput="filtrarClientes()">

            <input type="text" id="cliente_id" name="cliente_id" value="" hidden>
            <ul id="resultado-clientes"></ul>

            <label for="cliente_seleccionado">Cliente seleccionado:</label>
            <input type="text" id="cliente_seleccionado" name="cliente_seleccionado" value="" readonly class="cliente-seleccionada">
        </fieldset>

        <div id="carrito" class="carrito">
            <h2>
                <img src="../assets/img/cart-icon.png" alt="Icono Carrito" style="width: 30px; height: 30px; margin-right: 10px;">
                Carrito de compra
            </h2>

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
            <p id="subtotal-venta" class="subtotal-venta">Subtotal: $0</p>
            <p id="impuesto" class="impuesto">I.V.A 21%</p>
            <p id="total-venta" class="total-venta">Total: $0</p>
        </div>

        <fieldset class="fieldset">
            <legend>
                <img src="../assets/img/metodo-de-pago.png" alt="Icono" class="icono-ticket" /> Forma de pago
            </legend>
            <select id="forma_pago" name="formaPago" required class="select-forma-pago">
                <option value="">Seleccione forma de pago</option>
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
