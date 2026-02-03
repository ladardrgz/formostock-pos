<?php
session_start();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de datos geogr&aacute;ficos</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/IconoLog.ico">

    <!-- Hoja de estilos para el diseño del menú de navegación -->
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">

    <!-- CDN de Bootstrap para estilos principales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CDN de Bootstrap Icons para íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        html,
        body {
            background-image: url('../../assets/img/background-black.png');
            background-size: cover;
            background-repeat: no-repeat;
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
            padding: 20px;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            color: #060606;
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #503459;
        }

        nav {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #503459;
        }

        .form-control {
            border-radius: 5px;
            font-size: 14px;
            background-color: transparent;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .form-control:focus {
            box-shadow: none;
            outline: none;
            border-color: #7b5095;
        }

        .input-group-text {
            cursor: pointer;
        }

        .custom-btn {
            width: 100%;
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
            color: white;
        }

        .text-center {
            text-align: center;
            margin-bottom: 15px;
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

        .input-group {
            align-items: center;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            height: auto;
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

        select.form-select {
            border-radius: 5px;
            font-size: 14px;
            background-color: transparent;
            border: 1px solid #ccc;
        }

        select.form-select:focus {
            box-shadow: none;
            outline: none;
            border-color: #7b5095;
        }

        .help-link {
            display: inline-block;
            font-size: 16px;
            color: white;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease-in-out;
            border-radius: 5px;
            padding: 10px 15px;
            background: linear-gradient(45deg, #D1A7E3, #8A6BCF);
            box-shadow: 0 4px 6px rgba(138, 107, 207, 0.5);
            text-align: center;
        }

        .help-link:hover {
            color: #fff;
            background: linear-gradient(45deg, #8A6BCF, #D1A7E3);
            box-shadow: 0 8px 12px rgba(138, 107, 207, 0.7);
            transform: scale(1.1);
        }

        .help-link:focus {
            outline: none;
            transform: scale(1.1);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>

</head>

<body>

    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Nuevo barrio</h3>
            <form action="guardar_nuevo_barrio.php" method="POST">

                <p class="aviso">
                    <i class="bi bi-info-circle"></i> Primero ingresa el nombre del barrio, luego selecciona el país, provincia y localidad correspondientes.
                </p>

                <div class="form-group">
                    <label for="nombreBarrio">Nombre del barrio</label>
                    <input type="text" class="form-control" id="nombreBarrio" name="nombreBarrio" minlength="5" maxlength="50" placeholder="Nombre del barrio" required>
                </div>

                <div class="form-group">
                    <label for="pais">Selecciona un pa&iacute;s</label>
                    <select class="form-control" id="pais" name="pais" required onchange="cargarProvincias()">
                        <option value="">Seleccionar pa&iacute;s</option>
                    </select>
                    <a href="#" class="help-link" data-url="tb_paises.php">¿No encuentras tu país?</a>
                </div>

                <div class="form-group">
                    <label for="provincia">Selecciona una provincia</label>
                    <select class="form-control" id="provincia" name="provincia" required onchange="cargarLocalidades()">
                        <option value="">Seleccionar provincia</option>
                    </select>
                    <a href="#" class="help-link" data-url="tb_provincias.php">¿Dudas sobre la provincia?</a>
                </div>

                <div class="form-group">
                    <label for="localidad">Selecciona una localidad</label>
                    <select class="form-control" id="localidad" name="localidad" required>
                        <option value="">Seleccionar localidad</option>
                    </select>
                    <a href="#" class="help-link" data-url="tb_localidades.php">¿Necesitas encontrar tu localidad?</a>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="help-links.js"></script>

<script src="mesagges.js"></script>

<script src="data-loaders.js"></script>

<script>
    const sessionMessage = <?php echo json_encode($message); ?>;
</script>


</body>

</html>