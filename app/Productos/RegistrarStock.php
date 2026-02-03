<?php
session_start();
include '../modelos/conexion.php';

$idProducto = isset($_GET['idProducto']) ? intval($_GET['idProducto']) : 0;

if ($idProducto === 0) {
    $_SESSION['mensaje'] = 'Código de producto no válido.';
    header('Location: crearProducto.php'); 
    exit;
}

// Obtener sucursales para mostrar en el formulario
$querySucursales = "SELECT * FROM tb_sucursal";
$resultSucursales = $conection->query($querySucursales);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto | Cantidad</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.4.10/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: url('../assets/img/background-black.png') repeat;
        }

        nav {
            margin-bottom: 20px;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Alineación en la parte superior */
            min-height: calc(100vh - 80px); /* Ajusta según la altura de tu nav */
        }

        .caja {
            max-width: 600px; /* Ancho máximo del contenedor */
            width: 100%; /* Asegura que use el ancho completo en pantallas pequeñas */
            background-color: #f8f9fa; /* Color de fondo de la caja */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px; /* Espaciado interno */
            margin-top: 20px; /* Espaciado superior respecto al nav */
        }

        h1, h4 {
            text-align: center;
            margin-bottom: 10px; /* Espaciado inferior */
            color: #343a40; /* Color del texto */
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .btn {
            width: 100%;
            background-color: #7b5095; /* Color de fondo de los botones */
            color: white; /* Color del texto de los botones */
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .btn:hover {
            background-color: #693e77; /* Color de fondo al pasar el ratón */
            color: #fff; /* Color del texto al pasar el ratón */
        }

        .alert {
            margin-bottom: 20px; /* Espaciado inferior para el mensaje de alerta */
        }

        .aviso {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin: 20px 0;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            font-weight: 500;
        }

        .aviso i {
            margin-right: 8px; /* Espacio entre el ícono y el texto */
        }
    </style>
</head>
<body>
    <div id="container">
        <div class="caja">
            <h1>Ingreso de productos</h1>
            <p class="aviso">
                <i class="bi bi-info-circle"></i> Por favor, ingrese la cantidad de producto disponible en cada sucursal
            </p>
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-info text-center">
                    <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <form action="rcbGuardarStock.php" method="POST">
                <input type="hidden" name="idProducto" value="<?php echo $idProducto; ?>">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sucursal = $resultSucursales->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sucursal['nombreSucursal']); ?></td>
                                <td>
                                    <input type="number" name="stock[<?php echo $sucursal['idSucursal']; ?>]" value="0" class="form-control">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary btn-block">Guardar cantidad</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire("Éxito", "<?php echo $_SESSION['mensaje']; ?>", "<?php echo strpos($_SESSION['mensaje'], 'Error') !== false ? 'error' : 'success'; ?>");
                <?php unset($_SESSION['mensaje']); ?> // Eliminar el mensaje después de mostrarlo
            <?php endif; ?>
        };
    </script>
</body>
</html>

<?php
$conection->close();
?>
