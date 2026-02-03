<?php
include "modelos/conexion.php";
$queryTransacciones = "
    SELECT fp.idFormaPago,           
    fp.nombreFormaPago, 
    SUM(tp.montoTransaccionPago) AS montoTotal,
    MAX(tp.fechaTransaccionPago) AS ultimaTransaccion
    FROM tb_transacciones_pago_caja tp
    JOIN tb_formas_pago fp ON tp.forma_pago_id = fp.idFormaPago
    WHERE fp.idFormaPago IN (4, 1, 2, 3)  -- Solo formas de pago específicas
    GROUP BY fp.idFormaPago, fp.nombreFormaPago
    ORDER BY montoTotal DESC
";
$resultTransacciones = $conection->query($queryTransacciones);
$formasPago = [];
while ($row = $resultTransacciones->fetch_assoc()) {
    $formasPago[$row['idFormaPago']] = $row;
}
$queryClientes = "
    SELECT COUNT(idCliente) AS totalClientes
    FROM tb_clientes
";
$resultClientes = $conection->query($queryClientes);
$totalClientes = 0;
if ($resultClientes) {
    $rowClientes = $resultClientes->fetch_assoc();
    $totalClientes = $rowClientes['totalClientes'];
}
$queryProductos = "
    SELECT COUNT(idProducto) AS totalProductos
    FROM tb_productos
";
$resultProductos = $conection->query($queryProductos);
$totalProductos = 0;
if ($resultProductos) {
    $rowProductos = $resultProductos->fetch_assoc();
    $totalProductos = $rowProductos['totalProductos'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | FormoStock</title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/IconoLog.ico">

    <!-- CSS adicional -->
    <link href="assets/css/style_include_nav.css" rel="stylesheet">

    <?php include "includes/functions.php"; ?>

    <!-- Bootstrap Icons: Librería de iconos para Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

    <!-- CDN actualizado de FontAwesome sin atributo integrity -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 120px;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 100%;
            padding: 20px;
        }

        .card {
            display: flex;
            align-items: center;
            border-radius: 12px;
            padding: 20px;
            width: 180px;
            height: 200px;
            color: #fff;
            text-align: left;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            font-size: 40px;
            margin-right: 16px;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-5px);
            }
        }

        .card-content h3 {
            margin: 0;
            font-size: 22px;
        }

        .card-content p {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .card-content span {
            font-size: 14px;
            color: #f0f0f0;
        }

        .efectivo {
            background: linear-gradient(135deg, #503459, #DEBDDB);
        }

        .credito {
            background: linear-gradient(135deg, #DEBDDB, #503459);
        }

        .tarjeta {
            background: linear-gradient(135deg, #503459, #DEBDDB);
        }

        .transferencia {
            background: linear-gradient(135deg, #DEBDDB, #503459);
        }

        .clientes {
            background: linear-gradient(135deg, #503459, #DEBDDB);
        }

        .productos {
            background: linear-gradient(135deg, #DEBDDB, #503459);
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="card-container">
            <div class="card efectivo">
                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-content">
                    <h3>Efectivo</h3>
                    <p>Total: <?php echo isset($formasPago[4]) ? '$' . number_format($formasPago[4]['montoTotal'], 2) : '$0.00'; ?></p>
                </div>
            </div>

            <div class="card credito">
                <div class="card-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="card-content">
                    <h3>Tarjeta de crédito</h3>
                    <p>Total: <?php echo isset($formasPago[1]) ? '$' . number_format($formasPago[1]['montoTotal'], 2) : '$0.00'; ?></p>
                </div>
            </div>

            <div class="card tarjeta">
                <div class="card-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="card-content">
                    <h3>Tarjeta de débito</h3>
                    <p>Total: <?php echo isset($formasPago[2]) ? '$' . number_format($formasPago[2]['montoTotal'], 2) : '$0.00'; ?></p>
                </div>
            </div>

            <div class="card transferencia">
                <div class="card-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="card-content">
                    <h3>Transferencia bancaria</h3>
                    <p>Total: <?php echo isset($formasPago[3]) ? '$' . number_format($formasPago[3]['montoTotal'], 2) : '$0.00'; ?></p>
                </div>
            </div>

            <div class="card clientes">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <h3>Total de clientes</h3>
                    <p><?php echo number_format($totalClientes); ?></p>
                </div>
            </div>
            <div class="card productos">
                <div class="card-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="card-content">
                    <h3>Total de productos</h3>
                    <p><?php echo number_format($totalProductos); ?></p>
                </div>
            </div>
        </div>
    </section>
</body>

<?php include "includes/footer.php"; ?>

<!-- jQuery: Biblioteca JavaScript para simplificar el manejo del DOM y eventos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- JavaScript de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- SweetAlert CSS: Estilos para las alertas personalizadas de SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<!-- SweetAlert JS: Biblioteca para crear alertas personalizadas y estilizadas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
    // Función para cargar notificaciones
    function cargarNotificaciones() {
        $.ajax({
            url: 'includes/notificaciones.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const badge = $('.badge');
                const dropdownMenu = $('#notificationDropdown + .dropdown-menu');
                dropdownMenu.empty();

                if (data.length > 0) {
                    badge.text(data.length);
                    data.forEach(notificacion => {
                        const li = `<li><a class="dropdown-item" href="#">Bajo en stock: ${notificacion.descripcion}</a></li>`;
                        dropdownMenu.append(li);
                    });
                } else {
                    badge.text('0');
                    dropdownMenu.append('<li><a class="dropdown-item" href="#">No hay notificaciones</a></li>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });
    }

    $(document).ready(function() {
        cargarNotificaciones();
        setInterval(cargarNotificaciones, 3000);

        <?php if (!isset($_SESSION['sucursal_id'])): ?>
            swal({

                title: "¡Bienvenido a FormoStock!",
                text: "Para continuar debes abrir una sucursal, serás redirigido a la página de administración de sucursal...",
                imageUrl: "assets/img/newLogo-FormoStock.png",
                imageWidth: 100,
                imageHeight: 100,
                icon: "info",
                timer: 5000,
                confirmButtonColor: '#503459',
                showConfirmButton: true,
                confirmButtonText: "Aceptar",

            }, function() {
                window.location.href = "Sucursales/aperturaSucursalOperativa.php";
            });
        <?php endif; ?>
    });
</script>
</body>

</html>