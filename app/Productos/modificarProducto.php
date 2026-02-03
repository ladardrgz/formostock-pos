<?php
include '../modelos/conexion.php';
include 'fetch_impuestos.php';
include 'fetch_categorias.php';
include 'fetch_marcas.php';

$idProducto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idProducto <= 0) {
    die("No se recibió ningún producto.");
}

// Obtener los detalles del producto para editar
$query = "SELECT * FROM tb_productos WHERE idProducto = ?";
$stmt = $conection->prepare($query);
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    die("Producto no encontrado.");
}

$impuesto = new Impuesto($conection);
$impuestos = $impuesto->obtenerImpuestos();

$categoria = new Categoria($conection);
$categorias = $categoria->obtenerCategorias();

$marca = new Marca($conection);
$marcas = $marca->obtenerMarcas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar producto</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
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
        }

        .btn-primary:hover {
            background-color: #693e77;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include_once('nav_productos.php'); ?>
    <div id="container">
        <div class="caja">
            <h1 class="text-center">Actualizar información del producto</h1>
            <form id="edit-form" action="recibirModificarProducto.php" method="post">
                <input type="hidden" name="idProducto" value="<?php echo htmlspecialchars($idProducto); ?>">

                <!-- Código de barras -->
                <div class="form-group">
                    <label for="codigoBarrasProducto">Código de barras</label>
                    <input type="text" name="codigoBarrasProducto" id="codigoBarrasProducto" class="form-control" value="<?php echo htmlspecialchars($producto['codigoBarrasProducto']); ?>" maxlength="50" required>
                </div>

                <!-- Número de serie -->
                <div class="form-group">
                    <label for="numeroDeSerieProducto">Número de serie</label>
                    <input type="text" name="numeroDeSerieProducto" id="numeroDeSerieProducto" class="form-control" value="<?php echo htmlspecialchars($producto['numeroDeSerieProducto']); ?>" maxlength="50" required>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcionProducto">Descripción</label>
                    <input type="text" name="descripcionProducto" id="descripcionProducto" class="form-control" value="<?php echo htmlspecialchars($producto['descripcionProducto']); ?>" maxlength="150" required>
                </div>

                <!-- Precio -->
                <div class="form-group">
                    <label for="precioProducto">Precio</label>
                    <input type="number" name="precioProducto" id="precioProducto" class="form-control" value="<?php echo htmlspecialchars($producto['precioProducto']); ?>" step="0.01" required>
                </div>

                <!-- Stock mínimo -->
                <div class="form-group">
                    <label for="stockMinProducto">Stock mínimo</label>
                    <select name="stockMinProducto" id="stockMinProducto" class="form-control" required>
                        <option value="" disabled>Selecciona una cantidad</option>
                        <option value="1" <?php echo ($producto['stockMinProducto'] == 1) ? 'selected' : ''; ?>>1</option>
                        <option value="5" <?php echo ($producto['stockMinProducto'] == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ($producto['stockMinProducto'] == 10) ? 'selected' : ''; ?>>10</option>
                    </select>
                </div>

                <!-- Impuesto -->
                <div class="form-group">
                    <label for="impuesto_id">Impuesto</label>
                    <select name="impuesto_id" id="impuesto_id" class="form-control" required>
                        <?php foreach ($impuestos as $impuesto): ?>
                            <option value="<?php echo $impuesto['idDetalleImpuesto']; ?>" <?php echo ($impuesto['idDetalleImpuesto'] == $producto['impuesto_id']) ? 'selected' : ''; ?>>
                                <?php echo $impuesto['nombreImpuesto'] . ' - ' . $impuesto['valorDetalleImpuesto'] . '%'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Categoría -->
                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-control" required>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['idCategoriaProducto']; ?>" <?php echo ($cat['idCategoriaProducto'] == $producto['categoria_id']) ? 'selected' : ''; ?>>
                                <?php echo $cat['nombreCategoriaProducto']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Marca -->
                <div class="form-group">
                    <label for="marca_id">Marca</label>
                    <select name="marca_id" id="marca_id" class="form-control" required>
                        <?php foreach ($marcas as $mar): ?>
                            <option value="<?php echo $mar['idMarcaProducto']; ?>" <?php echo ($mar['idMarcaProducto'] == $producto['marca_id']) ? 'selected' : ''; ?>>
                                <?php echo $mar['nombreMarcaProducto']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="estado_producto_id" value="1">

                <button type="submit" class="btn-primary">Actualizar producto</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('edit-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            fetch('recibirModificarProducto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120'
                        }).then(() => {
                            window.location.href = 'dashboardProductos.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema con la solicitud.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#67f120'
                    });
                });
        });
    </script>
</body>

</html>