<?php
include_once '../modelos/conexion.php';
include_once '../controladores/Paginador.php';
include_once 'mostrarTablaOrdenesCompra.php';

$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$registros_por_pagina = isset($_GET['num_registros']) ? (int)$_GET['num_registros'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search = mysqli_real_escape_string($conection, $search);

$query_count = "
    SELECT COUNT(*) AS total
    FROM tb_ordenes_compra AS oc
    LEFT JOIN tb_personas_juridicas AS pj ON oc.proveedor_id = pj.idPersonaJuridica
    LEFT JOIN tb_estados_logicos AS el ON oc.estado_orden_id = el.idEstLog
    WHERE (pj.razonSocial LIKE '%$search%' OR oc.fechaOrden LIKE '%$search%')
";

$result_count = mysqli_query($conection, $query_count);
$total_registros = mysqli_fetch_assoc($result_count)['total'];

$paginador = new Paginador($pagina_actual, $total_registros, $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio | Órdenes de compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>

<body>
    <?php require_once 'nav_proveedores.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Órdenes de compra</h1>

        <!-- Formulario de búsqueda y selección de registros por página -->
        <form method="GET" action="ordenesDeCompra.php" class="mb-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Buscar por proveedor o fecha de orden..." value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>Fecha de orden</th>
                    <th>Proveedor</th>
                    <th>Monto total de orden</th>
                    <th>Estado</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $inicio = ($pagina_actual - 1) * $registros_por_pagina;
                $query_ordenes = "
                SELECT 
                    oc.idOrdenCompra, 
                    oc.fechaOrden, 
                    pj.razonSocial AS proveedor,
                    oc.totalOrdenCompra, 
                    el.nombreEstLog AS estadoOrden
                FROM 
                    tb_ordenes_compra AS oc
                LEFT JOIN 
                    tb_personas_juridicas AS pj ON oc.proveedor_id = pj.idPersonaJuridica
                LEFT JOIN 
                    tb_personas_fisicas AS pf ON pj.persona_fisica_id = pf.idPersonaFisica
                LEFT JOIN 
                    tb_estados_logicos AS el ON oc.estado_orden_id = el.idEstLog
                WHERE 
                    pj.razonSocial LIKE '%$search%' 
                    OR oc.fechaOrden LIKE '%$search%'
                ORDER BY 
                    oc.fechaOrden DESC
                LIMIT 
                    $inicio, $registros_por_pagina;
                ";
                $result_ordenes = mysqli_query($conection, $query_ordenes);

                while ($orden = mysqli_fetch_assoc($result_ordenes)) {
                    $fecha = DateTime::createFromFormat('Y-m-d', $orden['fechaOrden']);
                    $fecha_formateada = $fecha->format('d-m-Y');

                    echo "<tr>";
                    echo "<td>{$fecha_formateada}</td>";
                    echo "<td>{$orden['proveedor']}</td>";
                    echo "<td>{$orden['totalOrdenCompra']}&dollar;</td>";
                    echo "<td>{$orden['estadoOrden']}</td>";

                    $idOrdenCompra = $orden['idOrdenCompra'];

                    $ubicacionDocumento = "../uploads/documentos/orden_{$idOrdenCompra}_*";

                    $archivos = glob($ubicacionDocumento);

                    echo "<td>";
                    if (empty($archivos)) {
                        echo "<form action='cargarDocumento.php' method='POST' enctype='multipart/form-data' style='display: inline;'>
                            <input type='file' name='documento' id='fileInput_{$idOrdenCompra}' class='d-none' accept='.pdf,.doc,.docx,.txt' onchange='this.form.submit()'>
                            <input type='hidden' name='idOrdenCompra' value='{$idOrdenCompra}'>
                            <img src='../assets/img/subirArchivo.png' alt='Cargar documento' style='cursor: pointer; width: 40px;' onclick='confirmarCarga({$idOrdenCompra});'>
                        </form>";
                    } else {
                        echo "<img src='../assets/img/archivoGuardado.png' alt='Ver documento' style='cursor: pointer; width: 40px;' 
                            onclick='confirmarDescarga(\"{$archivos[0]}\")'>";
                    }
                    echo "</td>";
                }
                ?>
            </tbody>
        </table>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar la paginación -->
        <nav aria-label="Page navigation">
            <?php echo $paginador->mostrar_paginacion(); ?>
        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmarCarga(idOrdenCompra) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas cargar un nuevo documento para esta orden?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, cargar documento',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('fileInput_' + idOrdenCompra).click();
                }
            });
        }

        function confirmarDescarga(url) {
            Swal.fire({
                title: '¿Deseas previsualizar el documento?',
                text: "Haz clic en confirmar para ver el archivo.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí, ver documento',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</body>

</html>