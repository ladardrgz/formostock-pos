<?php
include('../../modelos/conexion.php');

$barriosPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $barriosPorPagina;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$localidadFiltro = isset($_GET['localidad']) ? $_GET['localidad'] : '';

$total_query = "SELECT COUNT(*) as total FROM tb_barrios b
JOIN tb_localidades l ON b.localidad_id = l.idLocalidad 
WHERE b.nombreBarrio LIKE '%$searchTerm%' 
AND (l.nombreLocalidad LIKE '%$localidadFiltro%' OR '$localidadFiltro' = '')";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_barrios = $total_row['total'];

$total_pages = ceil($total_barrios / $barriosPorPagina);

$query = "SELECT b.idBarrio, b.nombreBarrio, l.nombreLocalidad 
FROM tb_barrios b
JOIN tb_localidades l ON b.localidad_id = l.idLocalidad
WHERE b.nombreBarrio LIKE '%$searchTerm%' 
AND (l.nombreLocalidad LIKE '%$localidadFiltro%' OR '$localidadFiltro' = '')
LIMIT $barriosPorPagina OFFSET $offset";
$result = mysqli_query($conection, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir y editar tus ubicaciones</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        html,
        body {
            background-image: url('../../assets/img/background-black.png');
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
            overflow-y: auto;
        }

        .caja {
            background-color: #F8F9FA;
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            padding: 15px;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            color: #060606;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-shrink: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
            min-height: 150px;
            display: table;
        }

        table tbody tr.no-results {
            height: 100px;
            text-align: center;
            background-color: #f9f9f9;
        }

        table tbody tr.no-results td {
            text-align: center;
            color: #666;
            font-weight: bold;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #9b59b6;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            background-color: #fff;
            font-size: 14px;
        }

        .pagination a.active {
            background-color: #9b59b6;
            color: #fff;
        }

        .pagination a:hover {
            color: #fff;
            background-color: #744389;
        }

        nav {
            margin-bottom: 20px;
        }

        #btn-administrar {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #9b59b6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            width: 100%;
            max-width: 400px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        #btn-administrar img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            vertical-align: middle;
        }

        #btn-administrar:hover {
            background-color: #8e44ad;
        }

        #form-agregar {
            margin-top: 10px;
        }

        #form-agregar input[type="text"] {
            margin: 8px 0;
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 14px;
            box-sizing: border-box;
        }

        #form-agregar button[type="submit"] {
            background-color: #9b59b6;
            color: #fff;
            padding: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 8px;
        }

        #form-agregar button[type="submit"]:hover {
            background-color: #8e44ad;
        }

        .btn-eliminar {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

        .btn-eliminar img {
            width: 34px;
            height: 34px;
        }

        .btn-eliminar:hover img {
            opacity: 0.8;
        }

        button.btn.btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }

        button.btn.btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }

        .valor-input {
            margin: 8px 0;
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 14px;
            box-sizing: border-box;
        }

        .aviso {
            text-align: center;
            font-size: 15px;
            color: #333;
            margin: 20px 0;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            font-weight: 500;
        }
    </style>

</head>

<body>
    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Añadir y editar tus ubicaciones</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/administrarAjuste.png" alt="Icono">
                Administrar ubicaciones
            </button>
            <!-- Formulario de búsqueda -->
            <form method="GET" action="" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2 input-lila" placeholder="Ingrese un barrio que desee filtrar en la búsqueda" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <select name="localidad" class="form-control me-2">
                    <option value="">Todas las localidades</option>
                    <?php
                    // Consultar todas las localidades para el filtro
                    $localidades_query = "SELECT idLocalidad, nombreLocalidad FROM tb_localidades";
                    $localidades_result = mysqli_query($conection, $localidades_query);
                    while ($localidad = mysqli_fetch_assoc($localidades_result)) {
                        echo "<option value='" . htmlspecialchars($localidad['nombreLocalidad']) . "' " . ($localidadFiltro == $localidad['nombreLocalidad'] ? "selected" : "") . ">" . htmlspecialchars($localidad['nombreLocalidad']) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <p class="aviso">
                <i class="bi bi-info-circle"></i> Actualmente está visualizando un listado de barrios registrados en el sistema.
            </p>


            <table>
                <thead>
                    <tr>
                        <th>Nombre del barrio</th>
                        <th>Localidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombreBarrio']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombreLocalidad']); ?></td>
                                <td>
                                    <button class="btn-eliminar" data-id="<?php echo $row['idBarrio']; ?>">
                                        <img src="../../assets/img/boton-eliminar.ico" alt="Eliminar" title="Eliminar Barrio">
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="no-results">
                            <td colspan="3">No se encontraron resultados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>&localidad=<?php echo $localidadFiltro; ?>">&laquo; Anterior</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>&localidad=<?php echo $localidadFiltro; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>&localidad=<?php echo $localidadFiltro; ?>">Siguiente &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mostrarFormulario() {
            Swal.fire({
                title: '¿Deseas administrar las ubicaciones?',
                text: "Serás dirigido al formulario de gestión de direcciones, donde puedes modificar la información de barrios, localidades y más.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'frm_tb_barrio.php';
                }
            });
        }

        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6181e7',
                    cancelButtonColor: '#cd4646',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `eliminarBarrio.php?id=${id}`;
                    }
                });
            });
        });
    </script>
</body>

</html>