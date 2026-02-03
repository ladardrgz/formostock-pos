<?php
// Verificar si el rol del usuario está definido en la sesión
if (!isset($_SESSION['rol_id'])) {
    // Redireccionar a la página de inicio de sesión si no está definido
    header('Location: ../');
    exit; // Asegurar que el script se detenga después de redirigir
}

// Obtener el ID del rol del usuario desde la sesión
$rol_id = $_SESSION['rol_id'];

// Incluir archivo de conexión a la base de datos
require_once ('conexion.php');

// Consultar los módulos asociados al rol del usuario, filtrando solo los permisos activos
$query = "SELECT tb_permisos.descripcionPermiso FROM tb_rolesPermisos 
          INNER JOIN tb_permisos ON tb_rolesPermisos.permiso_id = tb_permisos.idPermiso
          WHERE tb_rolesPermisos.rol_id = $rol_id 
          AND tb_permisos.activoPermiso = 1"; // Solo permisos activos

$result = mysqli_query($conection, $query);

// Verificar si se ejecutó correctamente la consulta
if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conection));
}

// Obtener todos los módulos del usuario en un array
$permisos = array();
while ($row = mysqli_fetch_assoc($result)) {
    $permisos[] = $row['descripcionPermiso'];
}
?>
