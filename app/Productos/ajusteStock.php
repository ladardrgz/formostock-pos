<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../modelos/conexion.php");
session_start();

if (!isset($_SESSION['sucursal_id'])) {
    die("Error: No se encontró el ID de sucursal en la sesión.");
}

$sucursal_id = $_SESSION['sucursal_id'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuste de stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
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
            background-color: #7b5095;
            color: #fff;
        }
    </style>

</head>

<body>
    <?php include_once('nav_productos.php'); ?>
    <div id="container">
        <div class="caja">
            <h2>Ajuste de stock</h2>
            <form id="incrementarForm" action="ajustarInventario.php" method="POST">
                <input type="hidden" id="sucursal_id" name="sucursal_id" value="<?php echo $sucursal_id; ?>">

                <div class="form-group">
                    <label for="buscador_producto">Introduzca el nombre del producto que desea encontrar</label>
                    <input type="text" class="form-control" id="buscador_producto" placeholder="Escriba para buscar un producto...">
                    <div id="resultados_busqueda" class="list-group mt-2"></div>
                </div>

                <div class="form-group">
                    <label for="producto_preview">Producto seleccionado</label>
                    <input type="text" class="form-control" id="producto_preview" readonly>
                    <input type="hidden" id="producto_id" name="producto_id">
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad a incrementar</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1">
                </div>

                <button type="submit" class="btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Iniciando el script de ajuste de stock');

            $('#buscador_producto').on('input', function() {
                var searchText = $(this).val();
                console.log('Texto de búsqueda:', searchText);

                if (searchText.length > 1) {
                    $.ajax({
                        url: 'buscarProducto.php',
                        method: 'POST',
                        data: { query: searchText },
                        success: function(response) {
                            console.log('Respuesta de búsqueda:', response);
                            $('#resultados_busqueda').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la búsqueda del producto:', error);
                            alert('Error en la búsqueda del producto. Intente de nuevo.');
                        }
                    });
                } else {
                    $('#resultados_busqueda').empty();
                }
            });

            $(document).on('click', '.producto-item', function() {
                var productoId = $(this).data('id');
                var productoDescripcion = $(this).text();

                console.log('Producto seleccionado:', productoId, productoDescripcion);

                $('#producto_id').val(productoId);
                $('#producto_preview').val(productoDescripcion);
                $('#resultados_busqueda').empty();
            });

            $('#incrementarForm').on('submit', function(event) {
                event.preventDefault();
                var form = $(this);
                console.log('Enviando formulario con datos:', form.serialize());

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        console.log('Respuesta de incremento de stock:', response);
                        var data = JSON.parse(response);

                        if (data.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#67f120',
                                text: data.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#67f120',
                                text: data.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al enviar el formulario:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al incrementar el stock.',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
