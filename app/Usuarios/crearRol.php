<?php
session_start();
include '../modelos/conexion.php';

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreRol = mysqli_real_escape_string($conection, $_POST['nombreRol']);
    if (!empty($nombreRol)) {
        $checkRolQuery = "SELECT idRol FROM tb_roles WHERE nombreRol = '$nombreRol' AND activoRol = 1";
        $resultCheck = mysqli_query($conection, $checkRolQuery);
        if (mysqli_num_rows($resultCheck) > 0) {
            $mensaje = 'El nombre del rol ya existe. Por favor, elige otro.';
            $tipoMensaje = 'error';
        } else {
            $query = "INSERT INTO tb_roles (nombreRol) VALUES ('$nombreRol')";
            if (mysqli_query($conection, $query)) {
                $mensaje = 'Rol administrativo creado correctamente.';
                $tipoMensaje = 'success';
            } else {
                $mensaje = 'Hubo un problema al crear el rol.';
                $tipoMensaje = 'error';
            }
        }
    } else {
        $mensaje = 'Por favor, ingresa un nombre para el rol.';
        $tipoMensaje = 'error';
    }
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipoMensaje'] = $tipoMensaje;
    header("Location: crearRol.php");
    exit();
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear rol</title>
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        body {
            background-image: url('../assets/img/background-black.png');
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
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
    </style>
</head>

<body>
    <?php include 'nav_usuarios.php'; ?>

    <div class="container">
        <h1>Crear rol administrativo</h1>
        <form id="formCrearRol" action="crearRol.php" method="POST">
            <div class="form-group">
                <label for="nombreRol" class="form-label">Nombre del rol administrativo</label>
                <input type="text" id="nombreRol" name="nombreRol" class="form-control" required maxlength="30" placeholder="Ej. administrador">
            </div>
            <div class="text-center">
                <button type="button" class="btn custom-btn" onclick="crearRol()">Crear rol</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        $tipoMensaje = $_SESSION['tipoMensaje'];
        unset($_SESSION['mensaje']); 
        unset($_SESSION['tipoMensaje']); 

        echo "<script>
            Swal.fire({
                title: '" . ($tipoMensaje == 'success' ? '¡Éxito!' : 'Error') . "',
                text: '$mensaje',
                icon: '$tipoMensaje',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        </script>";
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function crearRol() {
            var nombreRol = $("#nombreRol").val().trim();
            if (nombreRol === "") {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, rellena el campo.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                });
            } else {
                $("#formCrearRol").submit();
            }
        }
    </script>
</body>

</html>