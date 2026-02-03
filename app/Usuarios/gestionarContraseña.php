<?php
session_start(); // Asegúrate de que la sesión esté iniciada
require '../modelos/conexion.php'; // Asegúrate de que la ruta sea correcta

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['idUsuario'];
    $contrasenaIngresada = mysqli_real_escape_string($conection, $_POST['contrasena']);

    // Consulta para verificar la contraseña
    $query = "SELECT contraseñaUsuario FROM tb_usuarios WHERE idUsuario = '$idUsuario'";
    $result = mysqli_query($conection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Verifica si la contraseña ingresada coincide con la almacenada
        if (md5($contrasenaIngresada) === $usuario['contraseñaUsuario']) { // Asegúrate de usar el mismo método de hash
            header("Location: cambiar_contrasena.php"); // Cambia a la página para cambiar la contraseña
            exit();
        } else {
            $_SESSION['mensaje'] = "La contraseña ingresada es incorrecta.";
            $_SESSION['alert_type'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Usuario no encontrado.";
        $_SESSION['alert_type'] = "error";
    }
}

mysqli_close($conection); // Cierra la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background-image: url(../assets/img/background-black.png);
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto; /* Añadido margen superior para espacio entre el nav y el formulario */
            width: 100%;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .alert-success {
            background-color: #28a745; /* Color verde para éxito */
        }

        .alert-danger {
            background-color: #dc3545; /* Color rojo para error */
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Asegura que el padding no sume al width */
        }

        button {
            background-color: #7B5095; /* Color del botón */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 35%;
            display: block; /* Asegura que el botón sea un bloque */
            margin: 0 auto; /* Centra el botón */
        }

        button:hover {
            background-color: #5e3e75; /* Color más oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
<?php include 'nav_usuarios.php'; ?>
    <div class="container">
        <h2>Verifica tu contraseña</h2>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert <?php echo $_SESSION['alert_type'] === 'error' ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo $_SESSION['mensaje']; ?>
                <?php unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <p>Para continuar debes ingresar tu contraseña actual</p>
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit">Continuar</button>
        </form>
    </div>
</body>
</html>
