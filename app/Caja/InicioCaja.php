<?php
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php'; // Cambiado el nombre del paginador
include_once 'mostrarTablaCaja.php'; // Cambiado el nombre de la función de mostrar
include_once 'estadoCaja.php'; // Asegúrate de tener una clase/función para manejar estados de caja

// Obtener los parámetros de la URL
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Escapar el término de búsqueda para evitar inyecciones SQL
$search = mysqli_real_escape_string($conection, $search);

// Consulta para contar el número total de cajas que coinciden con la búsqueda
$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_caja c
    LEFT JOIN tb_estados_logicos e ON c.estado_caja_id = e.idEstLog
    WHERE c.nombreCaja LIKE '%$search%'
";
$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

// Crear una instancia de Paginador para manejar la paginación
$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);

// Obtener los estados de caja
$estadoCajaClass = new EstadoCaja($conection);
$estadosCaja = $estadoCajaClass->obtenerEstados(); // Asegúrate de tener esta función
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio | Cajas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>
<body>
   <?php include 'MenuNavegacionCaja.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Cajas</h1>
        
        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="InicioCaja.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar cajas..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>Nombre de caja</th>
                    <th>Saldo inicial</th>
                    <th>Saldo actual</th>
                    <th>Fecha apertura</th>
                    <th>Fecha cierre</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    <?php
        // Obtener las cajas con paginación
        $inicio = ($pagina_actual - 1) * $registros_por_pagina;
        $cajas = obtenerCajas($inicio, $registros_por_pagina, $search); // Asegúrate de tener esta función

        foreach ($cajas as $caja) {
            echo "<tr>";
            echo "<td>{$caja['nombreCaja']}</td>";
            echo "<td>{$caja['saldoInicialCaja']}</td>";
            echo "<td>{$caja['saldoActualCaja']}</td>";
            echo "<td>{$caja['fechaAperturaCaja']}</td>";
            echo "<td>{$caja['fechaCierreCaja']}</td>";
            echo "<td>{$caja['nombreEstLog']}</td>";
            echo "<td>
                <!-- Botón de editar caja -->
                <button type='button' class='btn-img edit-button' data-id='{$caja['idCaja']}'>
                    <img src='../assets/img/boton-editar.ico' alt='Editar'>
                </button>
        
                <!-- Botón de eliminar caja -->
                <button type='button' class='btn-img delete-button' data-id='{$caja['idCaja']}'>
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
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="gestorEventosCaja.js"></script> 
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
                unset($_SESSION['mensaje']);
            }
            ?>
        });
    </script>
</body>
</html>
