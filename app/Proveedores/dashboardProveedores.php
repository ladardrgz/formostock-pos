<?php
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php';
include_once 'mostrarTablaProveedores.php';

// Obtener los parámetros de la URL
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Escapar el término de búsqueda para evitar inyecciones SQL
$search = mysqli_real_escape_string($conection, $search);

// Consulta para contar el número total de proveedores que coinciden con la búsqueda
$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_personas_juridicas pj
    LEFT JOIN tb_personas_fisicas pf ON pj.persona_fisica_id = pf.idPersonaFisica
    LEFT JOIN tb_detalle_documento dd ON pf.detalle_documento_id = dd.idDetalleDocumento
    LEFT JOIN tb_detalle_contacto dc ON pf.detalle_contacto_id = dc.idDetalleContacto
    WHERE pj.razonSocial LIKE '%$search%'
    OR pf.nombres LIKE '%$search%'
    OR pf.apellidos LIKE '%$search%'
    OR dd.valorDocumento LIKE '%$search%'
    OR dc.valorDetalleContacto LIKE '%$search%'
";
$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

// Crear una instancia de Paginador para manejar la paginación
$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio | Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>
<body>
<?php require_once 'nav_proveedores.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Proveedores</h1>
        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="dashboardProveedores.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar proveedores por razón social, documento o contacto..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
            <div class="form-group">
                <label for="num_registros">Mostrar</label>
                <select id="num_registros" name="num_registros" class="form-select custom-select" onchange="this.form.submit()" style="width: 70px; height: 35px;">
                    <option value="5" <?php echo $registros_por_pagina == 5 ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo $registros_por_pagina == 20 ? 'selected' : ''; ?>>20</option>
                    <option value="30" <?php echo $registros_por_pagina == 30 ? 'selected' : ''; ?>>30</option>
                    <option value="50" <?php echo $registros_por_pagina == 50 ? 'selected' : ''; ?>>50</option>
                </select>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Razón social</th>
                    <th>Nombre completo</th>
                    <th>Documento</th>
                    <th>Contacto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    <?php
        // Obtener los proveedores con paginación
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        $query_proveedores = "
            SELECT 
                pj.*, 
                CONCAT(pf.nombres, ' ', pf.apellidos) AS nombre_completo, 
                dd.valorDocumento AS documento,
                dc.valorDetalleContacto AS contacto
            FROM 
                tb_personas_juridicas pj
            LEFT JOIN 
                tb_personas_fisicas pf ON pj.persona_fisica_id = pf.idPersonaFisica
            LEFT JOIN 
                tb_detalle_documento dd ON pf.detalle_documento_id = dd.idDetalleDocumento
            LEFT JOIN 
                tb_detalle_contacto dc ON pf.detalle_contacto_id = dc.idDetalleContacto
            WHERE pj.razonSocial LIKE '%$search%'
            OR pf.nombres LIKE '%$search%'
            OR pf.apellidos LIKE '%$search%'
            OR dd.valorDocumento LIKE '%$search%'
            OR dc.valorDetalleContacto LIKE '%$search%'
            LIMIT $inicio, $registros_por_pagina
        ";
        $result_proveedores = mysqli_query($conection, $query_proveedores);

        while ($proveedor = mysqli_fetch_assoc($result_proveedores)) {
            echo "<tr>";
            echo "<td>{$proveedor['razonSocial']}</td>";
            echo "<td>{$proveedor['nombre_completo']}</td>";
            echo "<td>{$proveedor['documento']}</td>";
            echo "<td>{$proveedor['contacto']}</td>";
            echo "<td>
                <a href='modificarProveedor.php?id={$proveedor['idPersonaJuridica']}' class='btn-img edit-button'>
                    <img src='../assets/img/boton-editar.ico' alt='Editar'>
                </a>
                <button type='button' class='btn-img delete-button' data-id='{$proveedor['idPersonaJuridica']}' >
                    <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
                </button>
            </td>";
            echo "</tr>";
        }
    ?>
</tbody>
        </table>

        <!-- Mostrar la paginación -->
        <nav aria-label="Page navigation">
            <?php echo $paginador->mostrar_paginacion(); ?>
        </nav>
    </div>
    <script src="accionesBotonesProveedor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
