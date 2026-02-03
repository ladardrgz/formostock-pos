<?php
include('../../modelos/conexion.php');

// Verificar si hay un mensaje en la URL
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Paginación: configurar el número de tipos de documentos por página
$documentosPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $documentosPorPagina;

// Consultar la base de datos para obtener el total de tipos de documentos
$total_query = "SELECT COUNT(*) as total FROM tb_tipo_documentos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_documentos = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_documentos / $documentosPorPagina);

// Consultar los tipos de documentos a mostrar en la página actual
$query = "SELECT idTipoDocumento, nombreTipoDoc 
          FROM tb_tipo_documentos
          LIMIT $documentosPorPagina OFFSET $offset";
$result = mysqli_query($conection, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gesti&oacute;n de documentos</title>

    <!-- Enlace al ícono del sitio web -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/IconoLog.ico">

    <!-- Enlace al archivo de estilos personalizados del módulo de navegación -->
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">

    <!-- CDN de Bootstrap 5.3.3 para estilos y componentes responsive (grid, botones, etc.) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CDN de Bootstrap Icons para utilizar iconos de la biblioteca de Bootstrap -->
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
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
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
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
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
            font-size: 18px;
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
            <h3>Manejo de documentos de identificaci&oacute;n</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/administrarAjuste.png" alt="Icono">
                Añadir y editar tipos de documentos
            </button>

            <p class="aviso">
                <i class="bi bi-info-circle"></i> Actualmente est&aacute; visualizando un listado de tipos de documentos registrados en el sistema.
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Tipo de documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los resultados en una tabla
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombreTipoDoc']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn-eliminar' data-id='" . $row['idTipoDocumento'] . "'>";
                        echo "<img src='../../assets/img/boton-eliminar.ico' alt='Eliminar' title='Eliminar tipo de documento'>";
                        echo "</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enlace al CDN de jQuery 3.6.0, una popular biblioteca JavaScript que simplifica la manipulación del DOM, manejo de eventos, y AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CDN para la biblioteca SweetAlert2, utilizada para mostrar alertas estilizadas con diseño atractivo y personalizable -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función que se ejecuta cuando el documento está cargado
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');

            if (message === 'en_uso') {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Advertencia!',
                    text: 'Este tipo de documento está en uso. La eliminación está prohibida.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            } else if (message === 'eliminado') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'El tipo de documento ha sido eliminado correctamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            } else if (message === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Hubo un problema al intentar eliminar el tipo de documento. Intente nuevamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }
        });

        // Función para mostrar el formulario de administración
        function mostrarFormulario() {
            Swal.fire({
                title: '¿Deseas añadir o editar un tipo de documento?',
                text: "Serás dirigido al formulario para gestionar tipos de documentos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'frm_tb_documentos.php';
                }
            });
        }

        // Event listener para los botones de eliminar
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
                        window.location.href = `eliminar_tipo_documento.php?id=${id}`;
                    }
                });
            });
        });
    </script>
</body>

</html>