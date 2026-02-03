<?php
session_start(); // Asegúrate de que la sesión esté iniciada
require '../modelos/conexion.php'; // Asegúrate de que la ruta sea correcta

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['idUsuario'];
    $nuevaContrasena = mysqli_real_escape_string($conection, $_POST['nueva_contrasena']);
    $confirmarContrasena = mysqli_real_escape_string($conection, $_POST['confirmar_contrasena']);

    // Consulta para obtener la contraseña actual
    $query = "SELECT contraseñaUsuario FROM tb_usuarios WHERE idUsuario = '$idUsuario'";
    $result = mysqli_query($conection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Validaciones
        if (md5($nuevaContrasena) === $usuario['contraseñaUsuario']) {
            $_SESSION['mensaje'] = "La nueva contraseña no puede ser igual a la anterior.";
            $_SESSION['alert_type'] = "Error";
        } elseif (strlen($nuevaContrasena) < 8) {
            $_SESSION['mensaje'] = "La nueva contraseña debe tener al menos 8 caracteres.";
            $_SESSION['alert_type'] = "Error";
        } elseif ($nuevaContrasena !== $confirmarContrasena) {
            $_SESSION['mensaje'] = "Las contraseñas no coinciden.";
            $_SESSION['alert_type'] = "Error";
        } else {
            // Actualiza la contraseña
            $nuevaContrasenaHash = md5($nuevaContrasena);
            $updateQuery = "UPDATE tb_usuarios SET contraseñaUsuario = '$nuevaContrasenaHash' WHERE idUsuario = '$idUsuario'";
            if (mysqli_query($conection, $updateQuery)) {
                $_SESSION['mensaje'] = "Contraseña actualizada exitosamente.";
                $_SESSION['alert_type'] = "Éxito";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar la contraseña.";
                $_SESSION['alert_type'] = "Error";
            }
        }
    } else {
        $_SESSION['mensaje'] = "Usuario no encontrado.";
        $_SESSION['alert_type'] = "Error";
    }

    mysqli_close($conection); // Cierra la conexión a la base de datos

    // Redirige a la página de verificación de contraseña
    header("Location: verPerfil.php"); // Cambia a la página donde manejas los mensajes
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
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

        .form-group {
            margin-bottom: 15px;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #7B5095;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 35%;
            margin: 0 auto; /* Centra el botón */
            display: block; /* Necesario para centrar */
        }

        button:hover {
            background-color: #6a3e80;
        }

        /* Estilo para aumentar el tamaño y centrar el texto del botón de SweetAlert */
        .swal2-confirm {
            padding: 10px 20px; /* Ajustar el tamaño para que el texto esté más centrado */
            font-size: 1.1em; /* Aumentar tamaño de fuente */
            text-align: center; /* Centrar texto */
            min-width: 100px; /* Asegurarse de que el botón tenga un ancho mínimo */
        }
    </style>
    <script>
        function validateForm() {
            const nuevaContrasena = document.getElementById("nueva_contrasena").value;
            const confirmarContrasena = document.getElementById("confirmar_contrasena").value;

            // Verificar que los campos no estén vacíos
            if (!nuevaContrasena || !confirmarContrasena) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Los campos no pueden estar vacíos.',
                });
                return false; // Previene el envío del formulario
            }

            if (nuevaContrasena !== confirmarContrasena) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden.',
                });
                return false; // Previene el envío del formulario
            }

            if (nuevaContrasena.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La nueva contraseña debe tener al menos 8 caracteres.',
                });
                return false; // Previene el envío del formulario
            }

            // Si todas las validaciones pasan
            return true;
        }
    </script>
</head>
<body>
    <?php include 'nav_usuarios.php'; ?>
    <div class="container">
        <h2>Cambiar contraseña</h2>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <script>
                Swal.fire({
                    icon: "<?php echo $_SESSION['alert_type'] === 'Éxito' ? 'success' : 'error'; ?>",
                    title: "<?php echo $_SESSION['alert_type']; ?>",
                    text: "<?php echo $_SESSION['mensaje']; ?>",
                });
            </script>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nueva_contrasena">Nueva contraseña</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>
            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar nueva contraseña</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>
            <button type="button" onclick="if(validateForm()) { this.form.submit(); }">Cambiar contraseña</button>
        </form>
    </div>
</body>
</html>
