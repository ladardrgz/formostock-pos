<?php
require_once '../../modelos/conexion.php';

// Obtener las provincias desde la base de datos
$queryProvincias = "SELECT idProvincia, nombreProvincia FROM tb_provincias";
$resultProvincias = mysqli_query($conection, $queryProvincias);
$provincias = mysqli_fetch_all($resultProvincias, MYSQLI_ASSOC);

// Mostrar mensaje basado en el parámetro GET
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar localidad</title>
    <link rel="icon" href="../../assets/img/IconoLog.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        html,
        body {
            background-image: url('../../assets/img/background-black.png');
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        #container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            overflow-y: auto;
        }

        .caja {
            background-color: #F8F9FA;
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            padding: 15px;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            color: #060606;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #9b59b6;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            background-color: #fff;
            font-size: 14px;
        }

        .pagination a.active {
            background-color: #9b59b6;
            color: #fff;
        }

        .pagination a:hover {
            color: #fff;
            background-color: #744389;
        }

        nav {
            margin-bottom: 20px;
        }

        .btn-primary {
            margin-top: 20px;
            width: 30%;
            background-color: #7b5095;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #693E77;
        }

        #btn-administrar {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #9b59b6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            width: 100%;
            max-width: 400px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        #btn-administrar img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            vertical-align: middle;
        }

        #btn-administrar:hover {
            background-color: #8e44ad;
        }

        #form-agregar {
            margin-top: 10px;
        }

        #form-agregar input[type="text"] {
            margin: 8px 0;
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 14px;
            box-sizing: border-box;
        }

        #form-agregar button[type="submit"] {
            background-color: #9b59b6;
            color: #fff;
            padding: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 8px;
        }

        #form-agregar button[type="submit"]:hover {
            background-color: #8e44ad;
        }

        .btn-eliminar {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

        .btn-eliminar img {
            width: 34px;
            height: 34px;
        }

        .btn-eliminar:hover img {
            opacity: 0.8;
        }

        button.btn.btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }

        button.btn.btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }

        .valor-input {
            margin: 8px 0;
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 14px;
            box-sizing: border-box;
        }

        .aviso {
            text-align: center;
            font-size: 15px;
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
    </style>
</head>
<body>
    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Nueva localidad</h3>
            <form action="guardar_nueva_localidad.php" method="POST">
                <div class="form-group">
                    <label for="nombrelocalidad">Nombre de la localidad</label>
                    <input type="text" name="nombrelocalidad" id="nombrelocalidad" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="provincia_id">Provincia</label>
                    <select name="provincia_id" id="provincia_id" class="form-control" required>
                        <option value="">Seleccionar provincia</option>
                        <?php foreach ($provincias as $provincia): ?>
                            <option value="<?php echo $provincia['idProvincia']; ?>">
                                <?php echo htmlspecialchars($provincia['nombreProvincia']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn-primary">Registrar localidad</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const message = "<?php echo $message; ?>";
            if (message === "added") {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Localidad registrada correctamente.',
                    confirmButtonColor: '#9b59b6'
                });
            } else if (message === "error") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al registrar la localidad.',
                    confirmButtonColor: '#d33'
                });
            } else if (message === "empty") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos Vacíos',
                    text: 'Por favor, completa todos los campos.',
                    confirmButtonColor: '#f39c12'
                });
            }
        });
    </script>
</body>
</html>
