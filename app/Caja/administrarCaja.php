<?php
session_start();
require '../modelos/conexion.php';
$mensaje = null;
$error = null;
$estado_sucursal = 'inactiva';
$imagenEstado = '../assets/img/sucursalCerrada.png';
$colorMensaje = 'text-danger';

if (isset($_SESSION['sucursal_id'])) {
    $estado_sucursal = 'activa';
    $imagenEstado = '../assets/img/sucursalAbierta.png';
    $colorMensaje = 'text-success';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sucursal_id'])) {
    $sucursal_id = $_POST['sucursal_id'];

    $_SESSION['sucursal_id'] = $sucursal_id;

    $sqlSucursal = "SELECT nombreSucursal FROM tb_sucursal WHERE idSucursal = ?";
    if ($stmt = $conection->prepare($sqlSucursal)) {
        $stmt->bind_param("i", $sucursal_id);
        $stmt->execute();
        $resultadoSucursal = $stmt->get_result();

        if ($resultadoSucursal->num_rows > 0) {
            $sucursal = $resultadoSucursal->fetch_assoc();
            $mensaje = "Sucursal abierta correctamente " . $sucursal['nombreSucursal'];
            $estado_sucursal = 'activa';
            $imagenEstado = '../assets/img/sucursalAbierta.png';
            $colorMensaje = 'text-success';
        } else {
            $error = "Error al obtener el nombre de la sucursal.";
        }
        $stmt->close();
    } else {
        $error = "Error al preparar la consulta: " . $conection->error;
    }
}

$sqlSucursales = "SELECT * FROM tb_sucursal";
$resultadoSucursales = $conection->query($sqlSucursales);
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar sucursal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
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

        .estado-sucursal {
            font-size: 1.4rem;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #2d3436;
            margin-top: 20px;
        }

        .estado-sucursal img {
            width: 30px;
            height: 30px;
            margin-left: 10px;
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
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1.1rem;
            border: none;
            width: 50%;
            margin-top: 20px;
        }

        .btn-lila:hover {
            background-color: #9b009b;
        }

        .text-center {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .btn-sucursal {
            font-size: 16px;
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
            border: 2px solid #007bff;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
        }

        .btn-sucursal:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
<?php include 'MenuNavegacionCaja.php'; ?>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h3>Apertura de sucursal</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">

                    <div class="estado-sucursal">
                        <span>Actualmente la sucursal se encuentra</span>
                        <strong class="<?php echo $colorMensaje; ?>"><?php echo $estado_sucursal; ?></strong>
                        <img src="<?php echo $imagenEstado; ?>" alt="Estado de la sucursal">
                    </div>

                    <div class="info-text">
                        <img src="../assets/img/btn-inf.ico" alt="Información" class="icono-informacion">
                        Para comenzar a operar y abrir una caja en otra sección, pero primero debes seleccionar una sucursal.
                    </div>

                    <div class="form-group mb-3">
                        <label for="sucursal_id" class="form-label">Selecciona una sucursal</label>
                        <select name="sucursal_id" id="sucursal_id" class="form-select" required>
                            <option value="">Elige una sucursal para comenzar a operar</option>
                            <?php if ($resultadoSucursales->num_rows > 0): ?>
                                <?php while ($sucursal = $resultadoSucursales->fetch_assoc()): ?>
                                    <option value="<?php echo $sucursal['idSucursal']; ?>">
                                        <?php echo $sucursal['nombreSucursal']; ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">No hay sucursales disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-lila" id="abrirSucursalBtn">Abrir sucursal</button>
                    </div>
                    <div class="text-center">
                        <a href="javascript:void(0);" id="registroSucursal" class="btn-sucursal">¿No encuentras tu sucursal?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('abrirSucursalBtn').addEventListener('click', function(event) {
            var sucursalSelect = document.getElementById('sucursal_id');
            if (sucursalSelect.value === '') {
                event.preventDefault();

                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Por favor, selecciona una sucursal para continuar.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120'
                });
            }
        });
        document.getElementById("registroSucursal").addEventListener("click", function() {
            Swal.fire({
                title: "¿Deseas registrar una nueva sucursal?",
                text: "Serás redirigido a la página de registro de sucursales.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "registro_sucursal_apertura.php";
                }
            });
        });
    </script>

    <?php if (isset($mensaje)): ?>
        <script>
            let timerInterval;
            Swal.fire({
                title: "¡Sucursal abierta correctamente!",
                html: "<b></b> segundos restantes.",
                timer: 3000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then(() => {
                window.location.href = "../Caja/aperturaCajaOperativa.php";
            });
        </script>
    <?php elseif (isset($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        </script>
    <?php endif; ?>
</body>

</html>

<?php
$conection->close();
?>