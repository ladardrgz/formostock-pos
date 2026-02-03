<?php
session_start();
include '../modelos/conexion.php';
// Incluye el archivo que maneja la obtención de datos de impuestos desde la base de datos
include 'fetch_impuestos.php';
// Incluye el archivo que maneja la obtención de datos de categorías desde la base de datos
include 'fetch_categorias.php';
// Incluye el archivo que maneja la obtención de datos de marcas desde la base de datos
include 'fetch_marcas.php';
// Incluye el archivo que maneja la obtención de datos de proveedores desde la base de datos
include 'fetch_proveedores.php';

$impuesto = new Impuesto($conection);
$impuestos = $impuesto->obtenerImpuestos();
$categoria = new Categoria($conection);
$categorias = $categoria->obtenerCategorias();
$marca = new Marca($conection);
$marcas = $marca->obtenerMarcas();
$proveedor = new Proveedor($conection);
$proveedores = $proveedor->obtenerProveedores();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Nuevo producto</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <!-- CSS de Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Cargar Bootstrap primero para asegurar que sus estilos sean aplicados correctamente -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Cargar SweetAlert2 después de Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.min.css">

    <!-- Cargar estilos personalizados al final para asegurar que tengan prioridad -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- Cargar script de SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.all.min.js"></script>

    <!-- Cargar iconos de la librería de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    

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

        h1,
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group {
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
            <h1 class="text-center">Nuevo producto</h1>
            <form action="guardarProducto.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="codigoBarrasProducto">Código de barras</label>
                    <input type="text" name="codigoBarrasProducto" id="codigoBarrasProducto" class="form-control" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="numeroDeSerieProducto">Número de serie</label>
                    <input type="text" name="numeroDeSerieProducto" id="numeroDeSerieProducto" class="form-control" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="descripcionProducto">Descripción</label>
                    <input type="text" name="descripcionProducto" id="descripcionProducto" class="form-control" maxlength="150" required>
                </div>

                <div class="form-group">
                    <label for="precioProducto">Precio</label>
                    <input type="number" name="precioProducto" id="precioProducto" class="form-control" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="proveedor_id">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                        <option value="" disabled selected>Selecciona un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['idProveedor']; ?>">
                                <?php echo $proveedor['razonSocial']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stockMinProducto">Stock mínimo</label>
                    <select name="stockMinProducto" id="stockMinProducto" class="form-control" required>
                        <option value="" disabled selected>Selecciona una cantidad</option>
                        <option value="1">1</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stockMaxProducto">Stock máximo</label>
                    <select name="stockMaxProducto" id="stockMaxProducto" class="form-control" required>
                        <option value="" disabled selected>Selecciona una cantidad</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="garantiaProducto">Garantía</label>
                    <select name="garantiaProducto" id="garantiaProducto" class="form-control" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Ninguna">Ninguna</option>
                        <option value="1 año">1 año</option>
                        <option value="2 años">2 años</option>
                        <option value="3 años">3 años</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagenProducto">Imagen</label>
                    <div class="custom-file">
                        <input type="file" name="imagenProducto" id="imagenProducto" class="custom-file-input" required>
                        <label class="custom-file-label" for="imagenProducto">
                            <i class="bi bi-file-earmark-image"></i> Seleccionar archivo
                        </label>
                        <small id="fileName" class="form-text text-muted">Ningún archivo seleccionado</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="impuesto_id">Impuesto</label>
                    <select name="impuesto_id" id="impuesto_id" class="form-control" required>
                        <?php foreach ($impuestos as $impuesto): ?>
                            <option value="<?php echo $impuesto['idDetalleImpuesto']; ?>">
                                <?php echo $impuesto['nombreImpuesto'] . ' - ' . $impuesto['valorDetalleImpuesto'] . '%'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-control" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['idCategoriaProducto']; ?>">
                                <?php echo $categoria['nombreCategoriaProducto']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="marca_id">Marca</label>
                    <select name="marca_id" id="marca_id" class="form-control" required>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['idMarcaProducto']; ?>">
                                <?php echo $marca['nombreMarcaProducto']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="estado_producto_id" value="25">
                <input type="submit" value="Registrar producto" class="btn btn-primary btn-block">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('precioProducto').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Evitar el envío del formulario
                const value = this.value.replace(/[^0-9]/g, ''); // Solo permitir números

                if (value) {
                    // Añadir ".00" al final del valor ingresado
                    this.value = value + '.00';
                }
            }
        });

        document.getElementById('imagenProducto').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Ningún archivo seleccionado';
            const label = this.nextElementSibling; // Etiqueta asociada
            const fileNameDisplay = document.getElementById('fileName'); // Elemento para mostrar el nombre

            label.innerText = fileName; // Actualiza el texto de la etiqueta
            fileNameDisplay.innerText = fileName; // Muestra el nombre del archivo

            var formData = new FormData();
            formData.append('imagenProducto', this.files[0]);

            fetch('subir_imagen.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Éxito", "Imagen subida exitosamente: " + data.url, "success");
                    } else {
                        Swal.fire("Error", data.message || "Ocurrió un error al subir la imagen.", "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire("Error", "Ocurrió un error al procesar la solicitud.", "error");
                });
        });


        window.onload = function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire("Éxito", "<?php echo $_SESSION['mensaje']; ?>", "<?php echo strpos($_SESSION['mensaje'], 'Error') !== false ? 'error' : 'success'; ?>");
                <?php unset($_SESSION['mensaje']); ?> // Eliminar el mensaje después de mostrarlo
            <?php endif; ?>
        };
    </script>
</body>

</html>