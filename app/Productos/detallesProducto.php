<?php
// detallesProducto.php
include '../modelos/conexion.php';

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sucursalId = 1;

    $query = "
        SELECT 
            tb_productos.idProducto, 
            tb_productos.codigoBarrasProducto,
            tb_productos.numeroDeSerieProducto,
            tb_productos.descripcionProducto, 
            tb_productos.precioProducto, 
            tb_productos.garantiaProducto, 
            tb_productos.imagenProducto, 
            tb_productos.impuesto_id,
            CONCAT(tb_tipo_impuestos.nombreImpuesto, ' - ', tb_detalle_impuestos.valorDetalleImpuesto) AS impuestoDetalle, 
            tb_categorias_productos.nombreCategoriaProducto, 
            tb_marcas_productos.nombreMarcaProducto, 
            tb_estados_logicos.nombreEstLog,
            COALESCE(tb_inventario_sucursal.stockSucursal, 0) AS stockSucursal 
        FROM 
            tb_productos
        LEFT JOIN 
            tb_detalle_impuestos ON tb_productos.impuesto_id = tb_detalle_impuestos.idDetalleImpuesto
        LEFT JOIN 
            tb_tipo_impuestos ON tb_detalle_impuestos.tipo_impuesto_id = tb_tipo_impuestos.idTipoImpuesto
        LEFT JOIN 
            tb_categorias_productos ON tb_productos.categoria_id = tb_categorias_productos.idCategoriaProducto
        LEFT JOIN 
            tb_marcas_productos ON tb_productos.marca_id = tb_marcas_productos.idMarcaProducto
        LEFT JOIN 
            tb_estados_logicos ON tb_productos.estado_producto_id = tb_estados_logicos.idEstLog
        LEFT JOIN 
            tb_inventario_sucursal ON tb_productos.idProducto = tb_inventario_sucursal.producto_id 
            AND tb_inventario_sucursal.sucursal_id = ?
        WHERE 
            tb_productos.idProducto = ?";

    $stmt = $conection->prepare($query);
    $stmt->bind_param('ii', $sucursalId, $productId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        $producto = null;
    }
} else {
    $producto = null;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Información</title>

    <!-- Cargar estilos personalizados al final para asegurar que tengan prioridad -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- CSS de Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Cargar Bootstrap primero para asegurar que sus estilos sean aplicados correctamente -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Establecer el icono de la página que aparece en la pestaña del navegador -->
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <style>
        body {
            background-image: url(../assets/img/background-black.png);
            background-size: cover;
            color: #ffffff;
        }

        .container {
            margin-top: 50px;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
        }

        .product-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container-producto {
            text-align: center;
            justify-content: center;
        }

        p {
            font-size: 1.1rem;
            color: #495057;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .barcode-container {
            text-align: center;
            margin-top: 10px;
        }

        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .highlight {
            color: #503459;
            font-weight: bold;
        }

        .center-text {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    include('nav_productos.php');
    ?>
    <div class="container">
        <?php if ($producto): ?>
            <h1>Información de producto</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="container-producto">
                    <?php
                    $imagenProducto = $producto['imagenProducto'];
                    $imagenExistente = file_exists($imagenProducto) && !empty($imagenProducto);
                    ?>

                    <?php if ($imagenExistente): ?>
                        <img src="<?php echo $imagenProducto; ?>" alt="Imagen del Producto" class="product-image">
                    <?php else: ?>
                        <p class="center-text">No se pudo cargar la imagen</p>
                    <?php endif; ?>
                    </div>

                    <div class="barcode-container">
                        <h3>Código de barras</h3>
                        <svg id="barcode"></svg>
                        <p><?php echo $producto['codigoBarrasProducto']; ?></p>
                    </div>
                </div>
                <div class="col-md-6 product-info">
                    <p><span class="highlight">Número de serie:</span> <?php echo $producto['numeroDeSerieProducto']; ?></p>
                    <p><span class="highlight">Descripción:</span> <?php echo $producto['descripcionProducto']; ?></p>
                    <p><span class="highlight">Precio:</span> $<?php echo number_format($producto['precioProducto'], 2); ?></p>
                    <p><span class="highlight">Garantía:</span> <?php echo $producto['garantiaProducto']; ?></p>
                    <p><span class="highlight">Impuesto:</span> <?php echo $producto['impuestoDetalle']; ?></p>
                    <p><span class="highlight">Categoría:</span> <?php echo $producto['nombreCategoriaProducto']; ?></p>
                    <p><span class="highlight">Marca:</span> <?php echo $producto['nombreMarcaProducto']; ?></p>
                    <p><span class="highlight">Estado:</span> <?php echo $producto['nombreEstLog']; ?></p>
                    <p><span class="highlight">Cantidad en stock:</span> <?php echo $producto['stockSucursal']; ?></p>
                </div>
            </div>
        <?php else: ?>
            <h2>Producto no encontrado</h2>
        <?php endif; ?>
    </div>

    <!-- JS Libraries -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/JsBarcode.all.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script>
        const barcodeValue = "<?php echo $producto ? $producto['codigoBarrasProducto'] : ''; ?>";
        if (barcodeValue) {
            JsBarcode("#barcode", barcodeValue, {
                format: "CODE128",
                lineColor: "#000",
                width: 2,
                height: 30,
                displayValue: true
            });
        }
    </script>

</body>

</html>

<?php
$conection->close();
?>