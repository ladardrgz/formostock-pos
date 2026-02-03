<?php
// Cargar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['emailUsuario'];

    // Conectar a la base de datos y verificar si el correo existe
    include '../../modelos/conexion.php';

    $query = "SELECT * FROM tb_usuarios WHERE emailUsuario = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar nueva contraseña temporal y su expiración
        $nuevaContrasena = substr(md5(rand()), 0, 8);
        $hashedContrasenaTemporal = md5($nuevaContrasena);
        $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));  // Expira en 15 minutos

        // Actualizar la contraseña temporal y su expiración en la base de datos
        $updateQuery = "UPDATE tb_usuarios SET contraseñaTemporal = ?, expiracionContraseñaTemporal = ? WHERE emailUsuario = ?";
        $stmtUpdate = $conection->prepare($updateQuery);
        $stmtUpdate->bind_param("sss", $hashedContrasenaTemporal, $expiracion, $email);
        $stmtUpdate->execute();

        // Configuración de PHPMailer para enviar el correo
        $mail = new PHPMailer(true);

        try {
            // Configuraciones del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'ladardrgz@gmail.com';  // Tu correo Gmail
            $mail->Password = 'tmbz nfzy zjjw fkqi';   // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del remitente y destinatario
            $mail->setFrom('no-reply@formostock.com', 'Soporte FormoStock');  // Cambia con tu nombre y correo
            $mail->addAddress($email);

            // Contenido del correo sin logo
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8'; // Establecer el conjunto de caracteres a UTF-8
            $mail->Subject = 'Restablecer contraseña';
            $mail->Body = "
            <div style='text-align: center; font-family: Arial, sans-serif;'>
                <h1 style='color: #6A0DAD; font-size: 36px; margin-bottom: 20px;'>FormoStock</h1>
                <div style='border: 2px solid #6A0DAD; padding: 20px; border-radius: 10px; background-color: #f9f9f9;'>
                    <h2 style='color: #333; font-size: 28px; margin-bottom: 15px;'>Restablecer contraseña</h2>
                    <p style='font-size: 20px; color: #333;'>Tu nueva contraseña temporal es: 
                    <strong style='color: #6A0DAD; font-size: 22px;'>$nuevaContrasena</strong></p>
                    <p style='font-size: 16px; color: #555;'>Por favor, cambia tu contraseña al iniciar sesión para mayor seguridad.</p>
                </div>
                <hr style='margin-top: 30px; border: 0; height: 1px; background-color: #ccc;'>
                <p style='color: gray; font-size: 14px; margin-top: 20px;'>Este es un correo generado automáticamente de un solo uso. 
                <strong>No respondas a este mensaje.</strong></p>
                <p style='color: gray; font-size: 14px;'>Si necesitas asistencia, contáctanos a través de 
                <a href='mailto:soporte@formostock.com' style='color: #6A0DAD;'>soporte@formostock.com</a>.</p>
            </div>";

            // Enviar el correo
            $mail->send();
            // Mensaje de éxito
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Correo enviado correctamente. Revisa tu bandeja de entrada.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#5774CF'
                    });
                });
            </script>";
        } catch (Exception $e) {
            // Mensaje de error
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'El correo no pudo ser enviado. Error: {$mail->ErrorInfo}',
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#5774CF'
                    });
                });
            </script>";
        }
    } else {
        // Mensaje si el correo no se encuentra
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Correo electr&oacute;nico no encontrado.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#5774CF'
                });
            });
        </script>";
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de contrase&ntilde;a | FormoStock</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/IconoLog.ico">
    <!-- Incluir Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Incluir SweetAlert antes del código PHP -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url(../../assets/img/background-black.png);
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #6A0DAD;
            margin-bottom: 20px;
            font-size: 28px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            text-align: left;
        }

        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #6A0DAD;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #5a0c9e;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Restablecimiento de contrase&ntilde;a</h2>
        <form action="" method="POST">
            <label for="emailUsuario">Introduce tu correo electr&oacute;nico</label>
            <input type="email" name="emailUsuario" required>
            <button type="submit">Enviar</button>
        </form>
        <footer>
            Si no recuerdas tu correo electr&oacute;nico, por favor comunicate con un administrador
        </footer>
    </div>
</body>

</html>
