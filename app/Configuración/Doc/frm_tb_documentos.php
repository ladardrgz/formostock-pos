<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de tipo de documento</title>

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
    </style>
</head>

<body>

    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Nuevo documento</h3>
            <form action="guardar_nuevo_tipo_documento.php" method="POST">

                <p class="aviso">
                    <i class="bi bi-info-circle"></i> Debe ingresar el nombre del nuevo tipo de documento que desea registrar.
                </p>

                <div class="form-group">
                    <label for="nombreTipoDoc">Nombre del tipo de documento</label>
                    <input type="text" class="form-control" id="nombreTipoDoc" name="nombreTipoDoc" minlength="3" maxlength="50" placeholder="Ej. Pasaporte" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CDN para la biblioteca SweetAlert2, utilizada para mostrar alertas estilizadas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('error') && urlParams.get('error') === 'nombre_en_uso') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Este nombre de tipo de documento ya está en uso',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }

        if (urlParams.has('message') && urlParams.get('message') === 'added') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'El tipo de documento ha sido registrado exitosamente',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120'
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    </script>
</body>

</html>
</body>

</html>