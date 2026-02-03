<?php
session_start();

class SesionUsuario {

    // Iniciar la sesión del usuario
    public static function iniciarSesion($data) {
        $_SESSION['active'] = true;
        $_SESSION['idUsuario'] = $data['idUsuario'];
        $_SESSION['nombreCuentaUsuario'] = $data['nombreCuentaUsuario'];
        $_SESSION['emailUsuario'] = $data['emailUsuario'] ?? null; 
        $_SESSION['estado_usuario_id'] = $data['estado_usuario_id'];
        $_SESSION['persona_fisica_id'] = $data['persona_fisica_id'];
        $_SESSION['rol_id'] = $data['rol_id'];
    }

    // Verificar si la sesión está activa
    public static function sesionActiva() {
        return isset($_SESSION['active']) && $_SESSION['active'] === true;
    }

    // Obtener datos de la sesión del usuario
    public static function obtenerDatosUsuario() {
        if (self::sesionActiva()) {
            return [
                'idUsuario' => $_SESSION['idUsuario'],
                'nombreCuentaUsuario' => $_SESSION['nombreCuentaUsuario'],
                'emailUsuario' => $_SESSION['emailUsuario'],
                'estado_usuario_id' => $_SESSION['estado_usuario_id'],
                'persona_fisica_id' => $_SESSION['persona_fisica_id'],
                'rol_id' => $_SESSION['rol_id']
            ];
        }
        return null;
    }

    // Cerrar la sesión del usuario
    public static function cerrarSesion() {
        session_unset();
        session_destroy();
    }

    // Redirigir si la sesión está activa
    public static function redirigirSiSesionActiva($url) {
        if (self::sesionActiva()) {
            header('Location: ' . $url);
            exit;
        }
    }

    // Redirigir si la sesión no está activa
    public static function redirigirSiNoSesionActiva($url) {
        if (!self::sesionActiva()) {
            header('Location: ' . $url);
            exit;
        }
    }
}
?>
