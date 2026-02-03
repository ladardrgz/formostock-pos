<?php
session_start();
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php';

if (!$conection) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query_count = "
    SELECT COUNT(DISTINCT tb_clientes.idCliente) AS total
    FROM tb_clientes
    INNER JOIN tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
    LEFT JOIN tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
    LEFT JOIN tb_detalle_contacto ON tb_personas_fisicas.detalle_contacto_id = tb_detalle_contacto.idDetalleContacto
    WHERE (tb_personas_fisicas.nombres LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
    OR tb_personas_fisicas.apellidos LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
    OR tb_detalle_documento.valorDocumento LIKE '%" . mysqli_real_escape_string($conection, $search) . "%')
    AND tb_personas_fisicas.estado_persona_id = 1
";
$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);

$offset = ($pagina_actual - 1) * $registros_por_pagina;
$query_clientes = "
    SELECT DISTINCT
        CONCAT(tb_personas_fisicas.nombres, ' ', tb_personas_fisicas.apellidos) AS nombre_completo, 
        tb_personas_fisicas.fechaNacimiento AS fecha_nacimiento,
        tb_personas_fisicas.sexo AS sexo,
        tb_detalle_documento.valorDocumento AS documento,
        tb_detalle_contacto.valorDetalleContacto AS contacto, 
        CONCAT(
        IFNULL(tb_domicilios_personas.valorDomicilio, ''), ', ',
        IFNULL(tb_barrios.nombreBarrio, ''), ', ',
        IFNULL(tb_localidades.nombreLocalidad, ''), ', ',
        IFNULL(tb_provincias.nombreProvincia, ''), ', ',
        IFNULL(tb_paises.nombrePais, '')
        ) AS direccion_completa,
        tb_clientes.idCliente AS idCliente
        FROM 
            tb_clientes
        INNER JOIN 
            tb_personas_fisicas ON tb_clientes.persona_fisica_id = tb_personas_fisicas.idPersonaFisica
        LEFT JOIN 
            tb_detalle_documento ON tb_personas_fisicas.detalle_documento_id = tb_detalle_documento.idDetalleDocumento
        LEFT JOIN 
            tb_detalle_contacto ON tb_personas_fisicas.detalle_contacto_id = tb_detalle_contacto.idDetalleContacto
        LEFT JOIN 
            tb_domicilios_personas ON tb_personas_fisicas.idPersonaFisica = tb_domicilios_personas.persona_fisica_id
        LEFT JOIN 
            tb_domicilios ON tb_domicilios_personas.domicilio_id = tb_domicilios.idDomicilio
        LEFT JOIN 
            tb_barrios ON tb_domicilios.barrio_id = tb_barrios.idBarrio
        LEFT JOIN 
            tb_localidades ON tb_barrios.localidad_id = tb_localidades.idLocalidad
        LEFT JOIN 
            tb_provincias ON tb_localidades.provincia_id = tb_provincias.idProvincia
        LEFT JOIN 
            tb_paises ON tb_provincias.pais_id = tb_paises.idPais
        WHERE 
        (tb_personas_fisicas.nombres LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
        OR tb_personas_fisicas.apellidos LIKE '%" . mysqli_real_escape_string($conection, $search) . "%' 
        OR tb_detalle_documento.valorDocumento LIKE '%" . mysqli_real_escape_string($conection, $search) . "%')
        AND tb_personas_fisicas.estado_persona_id = 1
        LIMIT $offset, $registros_por_pagina
";
$result_clientes = mysqli_query($conection, $query_clientes);

// Obtener clientes en un array
$clientes = [];
if (mysqli_num_rows($result_clientes) > 0) {
    $clientes = mysqli_fetch_all($result_clientes, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/styleDashboard.css">

    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <?php
    include 'nav_clientes.php';
    ?>
    <div class="container mt-4">
        <div class="container mt-4 d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Clientes</h1>
        </div>

        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="dashboardClientes.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar clientes por documento o nombre completo..." value="<?php echo htmlspecialchars($search); ?>">
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

        <!-- Mostrar clientes en una tabla -->
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Documento</th>
                    <th>Nombre y apellido</th>
                    <th>Fecha de nacimiento</th>
                    <th>Sexo</th>
                    <th>Información de contacto</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($clientes)) {
                    foreach ($clientes as $cliente) {
                        $fecha_formateada = date("d/m/Y", strtotime($cliente['fecha_nacimiento']));
                        echo "<tr>";
                        echo "<td>{$cliente['documento']}</td>";
                        echo "<td>{$cliente['nombre_completo']}</td>";
                        echo "<td>{$fecha_formateada}</td>";
                        echo "<td>{$cliente['sexo']}</td>";
                        echo "<td>{$cliente['contacto']}</td>";
                        echo "<td>{$cliente['direccion_completa']}</td>";
                        echo "<td>
                        <a href='#' class='btn-img edit-button' data-id='{$cliente['idCliente']}'>
                            <img src='../assets/img/boton-editar.ico' alt='Editar'>
                        </a>
                        <button type='button' class='btn-img delete-button' data-id='{$cliente['idCliente']}'>
                            <img src='../assets/img/boton-eliminar.ico' alt='Eliminar'>
                        </button>
                        <a href='historial_cliente.php?id={$cliente['idCliente']}' class='btn-img btn-info'>
                            <img src='../assets/img/historial-de-pedidos.ico' alt='Historial'>
                        </a>
                    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron clientes</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Mostrar el paginador nuevamente al final -->
        <?php echo $paginador->mostrar_paginacion(); ?>
    </div>

    <!-- Incluir Bootstrap JS y SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/gestorEventosClientes.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['mensaje'])) {
                echo "Swal.fire({
                title: 'Éxito',
                text: '" . addslashes($_SESSION['mensaje']) . "',
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            });";
                unset($_SESSION['mensaje']);
            }
            ?>

            const historialButtons = document.querySelectorAll('.btn-info');
            historialButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const clienteId = this.getAttribute('href').split('=')[1];
                    Swal.fire({
                        title: '¿Ver historial?',
                        text: "Serás redirigido al historial del cliente.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#6181e7',
                        cancelButtonColor: '#cd4646',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'historial_cliente.php?id=' + clienteId;
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>