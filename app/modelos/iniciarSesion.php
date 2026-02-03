<?php
session_start();
$alert = '';

if (!empty($_SESSION['active'])) {
    // Si ya hay una sesión activa en PHP, redirige al usuario
    header('Location: http://localhost/FormoStock/app/paginaPrincipal.php');
    exit;
} else {
    if (!empty($_POST)) {
        if (empty($_POST['nombreCuentaUsuario']) || empty($_POST['contraseñaUsuario'])) {
            // Si los campos de usuario y contraseña están vacíos, muestra un mensaje
            $alert = 'Ingrese su usuario o correo electrónico y contraseña';
            $alert_class = 'alert-warning';
        } else {
            require_once "conexion.php";
            $userOrEmail = mysqli_real_escape_string($conection, $_POST['nombreCuentaUsuario']);
            $pass = mysqli_real_escape_string($conection, $_POST['contraseñaUsuario']);
            $hashedPass = md5($pass);  // Asegúrate de usar un método más seguro para hashear contraseñas, como bcrypt.

            // Verificar si existe la columna 'emailUsuario' en la tabla 'tb_usuarios'
            $queryCheck = "SHOW COLUMNS FROM tb_usuarios LIKE 'emailUsuario'";
            $resultCheck = mysqli_query($conection, $queryCheck);

            if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
                // Si existe, busca por nombre de cuenta o email
                $query = mysqli_query($conection, "SELECT * FROM tb_usuarios WHERE nombreCuentaUsuario = '$userOrEmail' OR emailUsuario = '$userOrEmail'");
            } else {
                // Si no existe, busca solo por nombre de cuenta
                $query = mysqli_query($conection, "SELECT * FROM tb_usuarios WHERE nombreCuentaUsuario = '$userOrEmail'");
            }

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);

                // Verificar si ya existe una sesión activa para este usuario
                $checkSessionQuery = "SELECT * FROM tb_sesiones WHERE idUsuario = ? AND activo = 1";
                $stmt = $conection->prepare($checkSessionQuery);
                $stmt->bind_param("i", $data['idUsuario']);
                $stmt->execute();
                $sessionResult = $stmt->get_result();

                if ($sessionResult->num_rows > 0) {
                    // Si ya existe una sesión activa, no permitir el inicio de sesión
                    $alert = 'Tu cuenta ya está iniciada en otra sesión. Para continuar, cierra sesión en esa ventana o pestaña';
                    $alert_class = 'alert-danger';
                } else {
                    // Verificar si la contraseña es correcta
                    if ($hashedPass === $data['contraseñaUsuario']) {
                        $_SESSION['active'] = true;
                        $_SESSION['idUsuario'] = $data['idUsuario'];
                        $_SESSION['nombreCuentaUsuario'] = $data['nombreCuentaUsuario'];
                        $_SESSION['emailUsuario'] = $data['emailUsuario'] ?? null;
                        $_SESSION['estado_usuario_id'] = $data['estado_usuario_id'];
                        $_SESSION['persona_fisica_id'] = $data['persona_fisica_id'];
                        $_SESSION['rol_id'] = $data['rol_id'];

                        // Crear una nueva sesión activa en la base de datos
                        $createSessionQuery = "INSERT INTO tb_sesiones (idUsuario, activo) VALUES (?, 1)";
                        $stmt = $conection->prepare($createSessionQuery);
                        $stmt->bind_param("i", $data['idUsuario']);
                        $stmt->execute();

                        // Redirigir a la página principal
                        header('Location: http://localhost/FormoStock/app/paginaPrincipal.php');
                        exit;
                    } 
                    // Verificar si la contraseña es temporal
                    elseif ($hashedPass === $data['contraseñaTemporal'] && strtotime($data['expiracionContraseñaTemporal']) > time()) {
                        // Redirigir a una página de cambio de contraseña temporal
                        $_SESSION['active'] = true;
                        $_SESSION['idUsuario'] = $data['idUsuario'];
                        $_SESSION['nombreCuentaUsuario'] = $data['nombreCuentaUsuario'];
                        $_SESSION['emailUsuario'] = $data['emailUsuario'] ?? null;
                        $_SESSION['estado_usuario_id'] = $data['estado_usuario_id'];
                        $_SESSION['persona_fisica_id'] = $data['persona_fisica_id'];
                        $_SESSION['rol_id'] = $data['rol_id'];

                        // Redirigir a página de cambio de contraseña temporal
                        header('Location: http://localhost/FormoStock/app/paginaPrincipal.php');
                        exit;
                    } else {
                        // Si la contraseña es incorrecta o la temporal ha expirado
                        $alert = 'Contraseña incorrecta o caducada';
                        $alert_class = 'alert-danger';
                    }
                }
            } else {
                // Si no se encuentra el usuario o el email
                $alert = 'Usuario o correo electrónico incorrectos';
                $alert_class = 'alert-danger';
            }
        }
    }
}
?>
