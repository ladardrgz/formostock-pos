<?php
include_once '../modelos/conexion.php';

// Obtener los parámetros del filtro
$role = isset($_GET['role_filter']) ? $_GET['role_filter'] : ''; // Asegúrate de que el nombre coincida con el del formulario
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construir la consulta SQL
$query = "
    SELECT 
        tb_usuarios.idUsuario,
        tb_detalle_documento.valorDocumento AS documento,
        tb_personas_fisicas.nombres AS nombre,
        tb_personas_fisicas.apellidos AS apellido,
        tb_usuarios.nombreCuentaUsuario AS nombre_usuario,
        tb_usuarios.emailUsuario AS correo,
        tb_roles.nombreRol AS rol
    FROM tb_usuarios
    INNER JOIN tb_roles ON tb_usuarios.rol_id = tb_roles.idRol
    INNER JOIN tb_personas_fisicas ON tb_usuarios.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    INNER JOIN tb_estados_logicos ON tb_usuarios.estado_usuario_id = tb_estados_logicos.idEstLog
    INNER JOIN tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    WHERE tb_estados_logicos.nombreEstLog = 'Activo'
";

// Añadir condiciones de búsqueda y filtro por rol
if ($role) {
    $query .= " AND tb_roles.idRol = '" . mysqli_real_escape_string($conection, $role) . "'";
}

if ($search) {
    $query .= " AND (
        tb_usuarios.nombreCuentaUsuario LIKE '%" . mysqli_real_escape_string($conection, $search) . "%'
        OR tb_usuarios.emailUsuario LIKE '%" . mysqli_real_escape_string($conection, $search) . "%'
        OR CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) LIKE '%" . mysqli_real_escape_string($conection, $search) . "%'
        OR tb_detalle_documento.valorDocumento LIKE '%" . mysqli_real_escape_string($conection, $search) . "%'
    )";
}

// Ejecutar la consulta
$result = mysqli_query($conection, $query);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    // Generar el contenido de la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['documento']}</td>";
        echo "<td>{$row['nombre']} {$row['apellido']}</td>";
        echo "<td>{$row['nombre_usuario']}</td>";
        echo "<td>{$row['correo']}</td>";
        echo "<td>{$row['rol']}</td>";
        echo "<td>
            <a href='#' class='btn-img edit-button' data-id='{$row['idUsuario']}'>
                <img src='../assets/img/boton-editar.ico' alt='Editar'>
            </a>
            <button type='button' class='btn-img delete-button' data-id='{$row['idUsuario']}'>
                <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
            </button>
        </td>";
        echo "</tr>";
    }
} else {
    // Si no hay resultados, mostrar un mensaje
    echo "<tr><td colspan='6' class='text-center'>No se encontraron usuarios.</td></tr>";
}

// Cerrar la conexión si es necesario
mysqli_close($conection);
?>
