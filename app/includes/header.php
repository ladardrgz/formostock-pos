<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['active'])) {
    header('Location: ../');
    exit;
}

// Incluir las funciones y la consulta de caja y sucursal
require_once 'modelos/conexion.php'; // Asegúrate de que la ruta sea correcta
$cajaSucursal = include 'obtenerCajaSucursal.php'; // Incluir el script para obtener caja y sucursal

// Verificar que el valor retornado sea un array
if (!is_array($cajaSucursal)) {
    $cajaSucursal = [
        'cajaInfo' => 'Información de la caja no disponible.',
        'sucursalInfo' => 'Información de la sucursal no disponible.'
    ];
}

// Función para obtener la fecha en horario de Argentina (functions.php)
fechaHora();

// URL de redirección del perfil
$perfilURL = "Usuarios/verPerfil.php";
?>

<header>
    <nav class="navbar navbar-expand-sm navbar-custom">
        <div class="container-fluid">
            <!-- Logo y título de la aplicación -->
            <a class="navbar-brand" href="paginaPrincipal.php">
                <h1 class="nombre-sistema">FormoStock</h1>
            </a>
            <div class="d-flex align-items-center">
                <!-- Icono de calendario -->
                <p class="info"><?php echo fechaHora(); ?><i class="bi bi-calendar-day mx-2"></i></p>
            </div>
            <!-- Mostrar la información de la caja y sucursal -->
            <p class="info mx-3">
                <?php echo $cajaSucursal['cajaInfo']; ?>
                <!-- Icono de caja al final -->
                <i class="bi bi-box mx-1"></i>
            </p>

            <p class="info mx-3">
                <?php echo $cajaSucursal['sucursalInfo']; ?>
                <!-- Icono de sucursal al final -->
                <i class="bi bi-shop mx-1"></i>
            </p>

            <!-- Redirigir al perfil del usuario -->
            <span class="user"><?php echo isset($_SESSION['nombreCuentaUsuario']) ? $_SESSION['nombreCuentaUsuario'] : 'Usuario'; ?></span>

            <!-- Enlace alrededor de la imagen para redirigir al perfil -->
            <a href="<?php echo $perfilURL; ?>">
                <img class="photouser mx-2" src="assets/img/user.png" alt="Usuario">
            </a>

            <!-- Botón de salir -->
            <a href="salir.php" class="btn btn-link">
                <img class="close" src="assets/img/salir.png" alt="Salir del sistema" title="Salir">
            </a>

            <div id="nav-buttons-container" class="d-flex align-items-center"></div>
        </div>
        </div>
    </nav>

    <?php include "nav.php"; ?>
    <script src="includes/button.js"></script>
</header>