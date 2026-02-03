<?php
session_start();
include("../modelos/conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio | Sucursales</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleDashboard.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>
<body>
    <?php include 'MenuNavegacionSucursales.php'; ?>
    <div class="container mt-4">
        <div class="container mt-4 d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Sucursales</h1>
            <!-- Botón de agregar nueva sucursal eliminado -->
        </div>

        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="InicioSucursal.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar sucursales..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
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
        </form>

        <?php
        include_once '../controladores/Paginador.php';
        include_once 'mostrarPaginador.php';

        // Obtener la página actual
        $pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Obtener el término de búsqueda
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Obtener el número de registros por página, con un valor predeterminado de 5
        $registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;

        // Consulta para obtener el total de sucursales con valores nulos en algún atributo
        $query_count = "
            SELECT COUNT(*) AS total
            FROM 
                tb_sucursal AS s
            LEFT JOIN 
                tb_personas_juridicas AS pj ON s.persona_juridica_id = pj.idPersonaJuridica
            LEFT JOIN 
                tb_personas_fisicas AS pf ON pj.persona_fisica_id = pf.idPersonaFisica
            LEFT JOIN 
                tb_domicilios_personas AS dp ON pf.idPersonaFisica = dp.persona_fisica_id
            LEFT JOIN 
                tb_domicilios AS d ON dp.domicilio_id = d.idDomicilio
            LEFT JOIN 
                tb_barrios AS b ON d.barrio_id = b.idBarrio
            LEFT JOIN 
                tb_localidades AS l ON b.localidad_id = l.idLocalidad
            LEFT JOIN 
                tb_provincias AS p ON l.provincia_id = p.idProvincia
            LEFT JOIN 
                tb_paises AS pa ON p.pais_id = pa.idPais
            WHERE 
                (s.nombreSucursal LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' OR
                s.nombreSucursal IS NULL OR
                pj.razonSocial IS NULL OR
                pf.nombres IS NULL OR
                d.descripcionDomicilio IS NULL OR
                b.nombreBarrio IS NULL OR
                l.nombreLocalidad IS NULL OR
                p.nombreProvincia IS NULL OR
                pa.nombrePais IS NULL)
        ";

        $result_count = mysqli_query($conection, $query_count);
        $total_registros = mysqli_fetch_assoc($result_count)['total'];

        // Crear una instancia de Paginador
        $paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
        ?>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nombre de sucursal</th>
                    <th>Responsable</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        <?php
        // Mostrar la tabla de sucursales con la paginación
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        $sucursales = obtenerSucursales($inicio, $registros_por_pagina);

        foreach ($sucursales as $sucursal) {
            echo "<tr>";
            echo "<td>{$sucursal['tb_sucursal_nombre']}</td>";
            echo "<td>{$sucursal['tb_personas_fisicas_responsable']}</td>";
            echo "<td>{$sucursal['tb_domicilios_direccion']}</td>";
            echo "<td>
                <a href='#' class='btn-img edit-button' data-id='{$sucursal['idSucursal']}'>
                    <img src='../assets/img/boton-editar.ico' alt='Editar'>
                </a>
                <button type='button' class='btn-img delete-button' data-id='{$sucursal['idSucursal']}'>
                    <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
                </button>
            </td>";
            echo "</tr>";
        }
        ?>
            </tbody>
        </table>

        <!-- Mostrar el paginador nuevamente al final -->
        <?php echo $paginador->mostrar_paginacion(); ?>

    </div>

    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Incluir SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Enlazar el archivo JavaScript -->
    <script src="sucursalesAcciones.js"></script>
    <script src="ButtonOnTheRight.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "Swal.fire({
                    title: 'Éxito',
                    text: '" . $_SESSION['mensaje'] . "',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Aceptar'
                });";
                // Limpiar el mensaje después de mostrarlo
                unset($_SESSION['mensaje']);
            }
            ?>
        });
    </script>
</body>
</html>
