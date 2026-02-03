<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Usuarios</title>
    <!-- Incluir Bootstrap CSS desde un CDN para estilos responsivos y componentes predefinidos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados del usuario -->
    <link rel="stylesheet" href="../assets/css/styleDashboard.css">

    <!-- Estilos personalizados del módulo de navegación -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- Incluir SweetAlert2 CSS desde un CDN para alertas estilizadas y modales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Favicon: Icono que se muestra en la pestaña del navegador -->
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

</head>

<body>
    <?php
    // Incluyo el menú de navegación para el tablero de usuarios
    include 'nav_usuarios.php';
    ?>
    <div class="container mt-4">
        <div class="container mt-4 d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Usuarios</h1>
        </div>
        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="dashboardUsuarios.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar usuarios por nombre de cuenta de usuario, correo electrónico, o nombre completo..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
            <div class="form-group">
                <label for="num_registros">Mostrar</label>
                <select id="num_registros" name="num_registros" class="form-select custom-select" onchange="this.form.submit()" style="width: 70px; height: 35px;">
                    <option value="5" <?php echo isset($_GET['num_registros']) && $_GET['num_registros'] == '5' ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo isset($_GET['num_registros']) && $_GET['num_registros'] == '10' ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo isset($_GET['num_registros']) && $_GET['num_registros'] == '20' ? 'selected' : ''; ?>>20</option>
                    <option value="30" <?php echo isset($_GET['num_registros']) && $_GET['num_registros'] == '30' ? 'selected' : ''; ?>>30</option>
                    <option value="50" <?php echo isset($_GET['num_registros']) && $_GET['num_registros'] == '50' ? 'selected' : ''; ?>>50</option>
                </select>
            </div>

            <!-- Selector de roles para filtrar usuarios -->
            <div class="form-group mt-3">
                <label for="role_filter">Filtrar por rol administrativo</label>
                <div class="input-group">
                    <select id="role_filter" name="role_filter" class="custom-select" onchange="this.form.submit()">
                        <option value="">Todos los roles</option>
                        <?php
                        include_once '../modelos/conexion.php';
                        $query_roles = "SELECT idRol, nombreRol FROM tb_roles";
                        $result_roles = mysqli_query($conection, $query_roles);
                        while ($row_role = mysqli_fetch_assoc($result_roles)) {
                            $selected = isset($_GET['role_filter']) && $_GET['role_filter'] == $row_role['idRol'] ? 'selected' : '';
                            echo "<option value='{$row_role['idRol']}' $selected>{$row_role['nombreRol']}</option>";
                        }
                        ?>
                    </select>
                    <button class="btn btn-primary ms-2" type="submit">Filtrar</button>
                </div>
            </div>
        </form>

        <?php
        include_once 'datosTablaUsuarios.php';
        include_once '../controladores/Paginador.php';

        $pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $role_filter = isset($_GET['role_filter']) ? $_GET['role_filter'] : '';

        $query_count = "
            SELECT COUNT(*) AS total
            FROM 
                tb_usuarios 
            INNER JOIN 
                tb_roles ON tb_usuarios.rol_id = tb_roles.idRol
            INNER JOIN 
                tb_personas_fisicas ON tb_usuarios.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
            INNER JOIN 
                tb_estados_logicos ON tb_usuarios.estado_usuario_id = tb_estados_logicos.idEstLog
            INNER JOIN 
                tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
            WHERE 
                tb_estados_logicos.nombreEstLog = 'Activo'
                AND (tb_usuarios.nombreCuentaUsuario LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
                    OR tb_usuarios.emailUsuario LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
                    OR CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
                    OR tb_detalle_documento.valorDocumento LIKE '%" . mysqli_real_escape_string($conection, $search) . "%')
        ";

        if ($role_filter) {
            $query_count .= " AND tb_roles.idRol = '" . mysqli_real_escape_string($conection, $role_filter) . "'";
        }

        $result_count = mysqli_query($conection, $query_count);
        $total_registros = mysqli_fetch_assoc($result_count)['total'];

        $paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
        ?>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Documento</th>
                    <th>Nombre y apellido</th>
                    <th>Nombre de usuario</th>
                    <th>Correo electrónico</th>
                    <th>Rol administrativo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $inicio = ($pagina_actual - 1) * $registros_por_pagina;
                $usuarios = obtenerUsuarios($inicio, $registros_por_pagina, $search, $role_filter); // Modificado para incluir el filtro

                $loggedUserId = $_SESSION['idUsuario'];

                foreach ($usuarios as $usuario) {
                    echo "<tr>";
                    echo "<td>{$usuario['documento']}</td>";
                    echo "<td>{$usuario['nombre']} {$usuario['apellido']}</td>";
                    echo "<td>{$usuario['nombre_usuario']}</td>";
                    echo "<td>{$usuario['correo']}</td>";
                    echo "<td>{$usuario['rol']}</td>";
                    echo "<td>";

                    if ($usuario['idUsuario'] != $loggedUserId) {
                        echo "<button type='button' class='btn-img delete-button' data-id='{$usuario['idUsuario']}'>
                <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
            </button>";
                    } else {
                        echo "<button type='button' class='btn-img delete-button' disabled title='No puedes eliminar tu propia cuenta'>
                <img src='../assets/img/boton-eliminar-disabled.ico' alt='Eliminar' style='opacity: 0.5;'>
            </button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
        <?php echo $paginador->mostrar_paginacion(); ?>

        <!-- Incluir Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Incluir SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Enlazar el archivo JavaScript -->
        <script src="../assets/js/gestorEventosUsuarios.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php
                if (isset($_SESSION['mensaje'])) {
                    echo "Swal.fire({
                        title: 'Éxito',
                        text: '" . $_SESSION['mensaje'] . "',
                        icon: 'success',
                        confirmButtonColor: '#6181e7',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#67f120'
                    });";
                    unset($_SESSION['mensaje']);
                }
                ?>
            });
        </script>
</body>

</html>