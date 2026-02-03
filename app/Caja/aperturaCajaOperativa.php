<?php
session_start();
require '../modelos/conexion.php';

if (!isset($_SESSION['sucursal_id'])) {
    echo '<!doctype html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apertura de caja operativa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="../assets/css/style_nav_module.css">
        <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    </head>
    <body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "info",
                title: "Aviso",
                text: "Para comenzar a operar y abrir una caja en otra sección, primero debes seleccionar una sucursal. Serás redirigido a la página para abrir una sucursal.",
                confirmButtonText: "Entendido"
            }).then(() => {
                window.location.href = "../Sucursales/aperturaSucursalOperativa.php"; 
            });
        </script>
    </body>
    </html>';
    exit();
}
$mensaje = null;
$error = null;
$estado_caja = 'inactiva';
$imagenEstado = '../assets/img/cajaCerrada.png';
$colorMensaje = 'text-danger';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['caja_id'])) {
    $caja_id = $_POST['caja_id'];

    $sqlActualizarCaja = "UPDATE tb_caja SET estado_caja_id = 40 WHERE idCaja = ?";

    if ($stmt = $conection->prepare($sqlActualizarCaja)) {
        $stmt->bind_param("i", $caja_id);
        if ($stmt->execute()) {
            $_SESSION['caja_id'] = $caja_id;
            $mensaje = "Caja abierta correctamente";
            $estado_caja = 'activa';
            $imagenEstado = '../assets/img/cajaAbierta.png';
            $colorMensaje = 'text-success';
            $redireccionar = true;
        } else {
            $error = "Error al abrir la caja: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error al preparar la consulta: " . $conection->error;
    }
}

$sqlCajas = "SELECT * FROM tb_caja";
$resultadoCajas = $conection->query($sqlCajas);

$estadoCaja = "<strong class='" . $colorMensaje . "'>cerrada</strong>";
$imagenEstado = "../assets/img/cajaCerrada.png";

if (isset($_SESSION['caja_id'])) {
    $cajaAbiertaId = $_SESSION['caja_id'];

    $sqlNombreCaja = "SELECT nombreCaja FROM tb_caja WHERE idCaja = ?";

    if ($stmt = $conection->prepare($sqlNombreCaja)) {
        $stmt->bind_param("i", $cajaAbiertaId);
        $stmt->execute();
        $stmt->bind_result($nombreCaja);

        if ($stmt->fetch()) {
            // Cambiar el estado a "abierta" y aplicarle el color
            $estadoCaja = "<strong class='" . $colorMensaje . "'>abierta</strong> corresponde a: " . $nombreCaja;
            $imagenEstado = "../assets/img/cajaAbierta.png";
        } else {
            $estadoCaja = "Caja no encontrada";
        }

        $stmt->close();
    } else {
        $estadoCaja = "Error al obtener el nombre de la caja: " . $conection->error;
    }
}
?>


<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar caja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background-image: url(../assets/img/background-black.png);
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 600px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .card-header {
            background-color: #800080;
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px 0;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .estado-caja {
            font-size: 1.4rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #2d3436;
            margin-top: 20px;
            gap: 5px;
            text-align: center;
            flex-wrap: wrap;
        }

        .estado-caja img {
            width: 100px;
            height: 100px;
        }

        .icono-informacion {
            width: 18px;
            height: 18px;
            vertical-align: middle;
        }

        .info-text {
            font-size: 1rem;
            text-align: center;
            color: #636e72;
            margin-top: 10px;
        }

        .form-label {
            font-size: 1.2rem;
            color: #2d3436;
        }

        .form-group {
            text-align: center;
            margin-top: 2px;
        }

        .form-select {
            font-size: 1rem;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #dfe6e9;
        }

        .btn-lila {
            background-color: #800080;
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1.1rem;
            border: none;
            width: 50%;
            margin-top: 20px;
        }

        .btn-lila:hover {
            background-color: #9b009b;
            color: #fff;
        }

        .text-center {
            display: block;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h3>Administrar caja operativa</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">

                    <div class="estado-caja">
                        Estado de caja: <?php echo $estadoCaja; ?>
                        <img src="<?php echo $imagenEstado; ?>" alt="Estado de la caja">
                    </div>

                    <div class="info-text">
                        <img src="../assets/img/btn-inf.ico" alt="Información" class="icono-informacion">
                        Para comenzar a operar con transacciones, primero debes abrir una caja.
                    </div>

                    <div class="form-group mb-3">
                        <label for="caja_id" class="form-label">Selecciona una caja</label>
                        <select name="caja_id" id="caja_id" class="form-select" required>
                            <option value="">Elige una caja para comenzar a operar</option>
                            <?php if ($resultadoCajas->num_rows > 0): ?>
                                <?php while ($caja = $resultadoCajas->fetch_assoc()): ?>
                                    <option value="<?php echo $caja['idCaja']; ?>"><?php echo $caja['nombreCaja']; ?></option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">No hay cajas disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-lila" id="abrirCajaBtn">Abrir caja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('abrirCajaBtn').addEventListener('click', function(event) {
        const selectCaja = document.getElementById('caja_id');
        if (selectCaja.value === '') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe seleccionar una caja antes de continuar.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        }
    });
</script>
<script>
    let timerInterval;

    <?php if (isset($redireccionar) && $redireccionar === true): ?>
        Swal.fire({
            title: "¡Caja abierta correctamente!",
            html: "Redirigiendo a la página de inicio en <b></b> segundos...",
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = `${Math.ceil(Swal.getTimerLeft() / 1000)}`;
                }, 1000);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "../paginaPrincipal.php";
            }
        });
    <?php endif; ?>
</script>
</body>

</html>