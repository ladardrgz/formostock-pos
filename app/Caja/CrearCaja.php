<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apertura de caja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS de Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Cargar Bootstrap primero para asegurar que sus estilos sean aplicados correctamente -->
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
    <?php include('MenuNavegacionCaja.php'); ?>

    <div id="container">
        <div class="caja">
            <h2 class="text-center">Apertura de caja</h2>
            <form action="ProcesarCaja.php" method="POST" id="formCaja">
                <div class="form-group">
                    <label for="nombreCaja" class="form-label">Nombre de caja</label>
                    <input type="text" class="form-control" id="nombreCaja" name="nombreCaja" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="saldoInicialCaja" class="form-label">Saldo inicial</label>
                    <input type="number" class="form-control" id="saldoInicialCaja" name="saldoInicialCaja" value="0.00" step="0.01" required>
                </div>

                <input type="hidden" id="saldoActualCaja" name="saldoActualCaja" value="0.00">

                <?php
                $fechaApertura = date('Y-m-d H:i:s');
                ?>
                <input type="hidden" id="fechaAperturaCaja" name="fechaAperturaCaja" value="<?php echo htmlspecialchars($fechaApertura); ?>">

                <div class="form-group">
                    <label for="fechaCierreCaja" class="form-label">Fecha de cierre</strong></label>
                    <input type="datetime-local" class="form-control" id="fechaCierreCaja" name="fechaCierreCaja">
                </div>

                <input type="hidden" id="estado_caja_id" name="estado_caja_id" value="41">

                <input type="hidden" id="montoArqueoCaja" name="montoArqueoCaja">

                <button type="submit" class="btn btn-primary">Registrar caja</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            Swal.fire({
                title: 'Caja registrada con éxito',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (status === 'error') {
            Swal.fire({
                title: 'Error',
                text: 'Error al registrar la caja. Por favor, intenta nuevamente.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    </script>

</body>

</html>