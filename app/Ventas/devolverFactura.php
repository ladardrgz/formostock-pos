<?php
include_once '../modelos/conexion.php';

if (isset($_GET['id'])) {
    $facturaId = (int)$_GET['id'];
    $queryFactura = "SELECT * FROM tb_factura_cabecera WHERE idFaCab = $facturaId";
    $resultFactura = mysqli_query($conection, $queryFactura);

    if (mysqli_num_rows($resultFactura) > 0) {
        $factura = mysqli_fetch_assoc($resultFactura);
    } else {
        echo "Factura no encontrada.";
        exit();
    }
} else {
    echo "Código de factura no proporcionado.";
    exit();
}

$queryProductos = "
    SELECT 
        tb_factura_detalle.idFaDet, 
        tb_productos.idProducto, 
        tb_productos.descripcionProducto, 
        tb_productos.numeroDeSerieProducto, 
        tb_factura_detalle.cantidadProductoFaDet, 
        tb_factura_detalle.subTotalFaDet
    FROM 
        tb_factura_detalle
    JOIN 
        tb_productos ON tb_factura_detalle.producto_id = tb_productos.idProducto
    WHERE 
        tb_factura_detalle.factura_cabecera_id = $facturaId
";
$resultProductos = mysqli_query($conection, $queryProductos);

$queryNotas = "SELECT idTipoNota, tipoNota FROM tb_tipos_notas";
$resultNotas = mysqli_query($conection, $queryNotas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar devolución</title>

    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.all.min.js"></script>

    <style>
        body {
            background: url('../assets/img/background-black.png') repeat
        }

        nav {
            margin-bottom: 20px;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: calc(100vh - 80px);
        }

        .caja {
            max-width: 600px;
            width: 100%;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 15px;
        }

        .form-check.producto-item {
            margin-bottom: 20px;
        }

        .producto-info {
            font-style: italic;
            color: #666;
            margin-left: 5px;
        }

        .cantidadDevolucion {
            width: 40%;
            margin-top: 8px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .cantidadDevolucion:focus {
            border-color: #7b5095;
            outline: none;
            box-shadow: 0 0 5px rgba(123, 80, 149, 0.3);
        }

        .form-group {
            text-align: center;
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .btn {
            width: 100%;
        }

        .custom-btn {
            width: 30%;
            background-color: #7b5095;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .custom-btn:hover {
            background-color: #693e77;
            color: #fff;
        }

        .text-center {
            text-align: center;
            margin-bottom: 15px;
        }

        .input-group-text {
            background-color: #7b5095;
            color: white;
        }

        .input-group-text .bi {
            margin: 0;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .btn-primary {
            width: 30%;
            background-color: #7b5095;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #693e77;
            color: #fff;
        }

        .form-check-input:checked {
            background-color: #7b5095;
            border-color: #7b5095;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #ccc;
            background-color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .form-check-input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(123, 80, 149, 0.5);
        }
    </style>
</head>

<body>
    <?php include_once('nav_ventas.php'); ?>
    <div id="container">
        <div class="caja">
            <h2>Devolución de productos con factura N° <?php echo $facturaId; ?></h2>

            <form id="devolucionForm" method="POST" action="procesarDevolucion.php" onsubmit="return verificarSerie()">
                <input type="hidden" name="factura_id" value="<?php echo $facturaId; ?>">

                <div class="form-group">
                    <label for="motivoDevolucion" class="form-label">Motivo de la devolución</label>
                    <input type="text" class="form-control" id="motivoDevolucion" name="motivo_devolucion" placeholder="Ej: Cliente insatisfecho" required>
                </div>

                <h3>Productos de la factura</h3>
                <?php while ($producto = mysqli_fetch_assoc($resultProductos)) { ?>
                    <div class="form-check producto-item">
                        <input class="form-check-input producto-checkbox"
                            type="checkbox"
                            name="productos_seleccionados[]"
                            value="<?php echo $producto['idProducto']; ?>"
                            data-id="<?php echo $producto['idFaDet']; ?>"
                            data-cantidad="<?php echo $producto['cantidadProductoFaDet']; ?>"
                            data-serie="<?php echo $producto['numeroDeSerieProducto']; ?>">

                        <label class="form-check-label" for="producto-<?php echo $producto['idProducto']; ?>">
                            <?php echo $producto['descripcionProducto']; ?>
                            <span class="producto-info">
                                (Cantidad: <?php echo $producto['cantidadProductoFaDet']; ?>, Subtotal: <?php echo $producto['subTotalFaDet']; ?>)
                            </span>
                        </label>

                        <input type="number"
                            class="form-control cantidadDevolucion"
                            name="cantidad_devolucion[<?php echo $producto['idProducto']; ?>]"
                            min="1"
                            max="<?php echo $producto['cantidadProductoFaDet']; ?>"
                            placeholder="Cantidad a devolver">
                        <input type="text"
                            class="form-control mt-2"
                            name="numero_serie[<?php echo $producto['idProducto']; ?>]"
                            placeholder="Número de serie" onblur="verificarNumeroSerie(<?php echo $producto['idProducto']; ?>, this.value)">
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="tipoDevolucion" class="form-label">Tipo de devolución</label>
                    <select class="form-select" id="tipoDevolucion" name="tipo_devolucion" required>
                        <option value="">Seleccione el tipo de devolución</option>
                        <option value="Parcial">Parcial</option>
                        <option value="Total">Total</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="condicionesDeEntrega" class="form-label">Condiciones de entrega</label>
                    <input type="text" class="form-control" id="condicionesDeEntrega" name="condiciones_entrega" placeholder="Ej: Entrega en buen estado" required>
                </div>

                <div class="form-group">
                    <label for="tipoNotaId" class="form-label">Seleccione el tipo de nota que desea generar</label>
                    <select class="form-select" id="tipoNotaId" name="tipo_nota_id" required>
                        <option value="">Seleccionar tipo de nota</option>
                        <?php while ($nota = mysqli_fetch_assoc($resultNotas)) { ?>
                            <option value="<?php echo $nota['idTipoNota']; ?>"><?php echo $nota['tipoNota']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn-primary">Confirmar devolución</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function verificarNumeroSerie(productoId, numeroSerie) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'verificar_serie.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.existe) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Serie válida',
                            text: 'El número de serie ingresado es correcto.',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Número de serie incorrecto',
                            text: 'El número de serie ingresado no es válido para este producto.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            };
            xhr.send('producto_id=' + productoId + '&numero_serie=' + numeroSerie);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>