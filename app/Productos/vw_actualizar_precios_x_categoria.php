<?php
// Incluir conexión a la base de datos
include '../modelos/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización masiva</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <!-- CSS de Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Cargar SweetAlert2 después de Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.min.css">

    <!-- Cargar estilos personalizados al final para asegurar que tengan prioridad -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- Cargar iconos de la librería de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Estilos personalizados */
        body {
            margin: 0;
            padding: 0;
            background: url('../assets/img/background-black.png') repeat;
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

        .btn-primary {
            width: 30%;
            background-color: #7b5095;
            color: white;
            margin: 10px auto 15px auto;
            display: block;
            border: none;
            border-radius: 5px;
            font-size: 14px;
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
            <h1>Actualización masiva</h1>
            <img src="../assets/img/ActualizarPreciosPorcentaje.png" alt="Actualizar precios" style="display: block; margin: 20px auto; max-width: 20%; height: auto;">
            <form id="update-form">
                <div class="form-group">
                    <label for="categoria_id">Seleccione una categoría</label>
                    <select name="categoria_id" id="categoria_id" required>
                        <?php
                        // Obtener categorías
                        $query = "SELECT idCategoriaProducto, nombreCategoriaProducto FROM tb_categorias_productos";
                        $result = $conection->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['idCategoriaProducto'] . '">' . $row['nombreCategoriaProducto'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay categorías disponibles</option>';
                        }

                        $conection->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="porcentaje_incremento">Ingrese el porcentaje que desea incrementar</label>
                    <select name="porcentaje_incremento" id="porcentaje_incremento" required>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                        <option value="30">30%</option>
                        <option value="40">40%</option>
                        <option value="50">50%</option>
                        <option value="60">60%</option>
                        <option value="70">70%</option>
                        <option value="80">80%</option>
                        <option value="90">90%</option>
                        <option value="100">100%</option>
                    </select>
                </div>

                <input type="submit" class="btn btn-primary" value="Actualizar precios">
            </form>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('actualizar_precios_x_categoria.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la respuesta: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema con la solicitud. Verifique su conexión o contacte al administrador.'
                    });
                });
        });
    </script>
</body>

</html>
