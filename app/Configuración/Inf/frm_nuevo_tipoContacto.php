<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de tipo de contacto</title>

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
    </style>
</head>

<body>

    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Nuevo tipo de contacto</h3>
            <form id="formTipoContacto" action="guardar_nuevo_tipoContacto.php" method="POST">

                <p class="aviso">
                    <i class="bi bi-info-circle"></i> Ingresa el nombre del nuevo tipo de contacto que deseas registrar.
                </p>

                <div class="form-group">
                    <label for="nombreTipoContacto">Nombre del tipo de contacto</label>
                    <input type="text" class="form-control" id="nombreTipoContacto" name="nombreTipoContacto" minlength="3" maxlength="50" placeholder="Ej. Correo electrónico" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="custom-btn">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message'); 
        if (message) {
            if (message === 'nombre_existente') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre de tipo de contacto ya existe.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                });
            } else if (message === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al registrar el tipo de contacto.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                });
            }
        }
    </script>
    
</body>

</html>