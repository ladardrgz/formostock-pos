<?php
require_once "../../modelos/conexion.php";

// Obtener la lista de países para el formulario
$queryPaises = "SELECT idPais, nombrepais FROM tb_paises";
$resultPaises = mysqli_query($conection, $queryPaises);
$paises = mysqli_fetch_all($resultPaises, MYSQLI_ASSOC);

// Configurar paginación
$provinciasPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $provinciasPorPagina;

// Obtener el total de provincias para calcular la paginación
$total_query = "SELECT COUNT(*) as total FROM tb_provincias";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_provincias = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_provincias / $provinciasPorPagina);

// Obtener la lista de provincias con sus países correspondientes, con paginación
$queryProvincias = "SELECT pr.idProvincia, p.nombrepais AS pais, pr.nombreProvincia AS provincia
                    FROM tb_provincias pr
                    INNER JOIN tb_paises p ON pr.pais_id = p.idPais
                    LIMIT $provinciasPorPagina OFFSET $offset";
$resultProvincias = mysqli_query($conection, $queryProvincias);
$provincias = mysqli_fetch_all($resultProvincias, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de provincias</title>
    <link rel="icon" href="../../assets/img/IconoLog.ico" type="image/x-icon">
    <!-- Hoja de estilos para el diseño del menú de navegación -->
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <!-- CDN de Bootstrap para estilos principales -->
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
            <h3>Provincias</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/administrarAjuste.png" alt="Icono">
                Agregar una provincia
            </button>

            <p class="aviso">
                <i class="bi bi-info-circle"></i> Actualmente está visualizando un listado de barrios registrados en el sistema.
            </p>

            <!-- Formulario para registrar una nueva provincia -->
            <div id="form-agregar" style="display:none;">
                <form id="form-provincia" method="POST">
                    <div class="form-group">
                        <label for="nombreprovincia">Nombre de la provincia</label>
                        <input type="text" id="nombreprovincia" name="nombreprovincia" required>
                    </div>
                    <div class="form-group">
                        <label for="pais_id">País</label>
                        <select id="pais_id" name="pais_id" required>
                            <option value="">Selecciona un país</option>
                            <?php foreach ($paises as $pais) : ?>
                                <option value="<?php echo $pais['idPais']; ?>"><?php echo $pais['nombrepais']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Registrar" class="btn">
                    </div>
                </form>
            </div>

            <!-- Tabla de provincias -->
            <table>
                <thead>
                    <tr>
                        <th>País</th>
                        <th>Provincia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($provincias as $provincia) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($provincia['pais']); ?></td>
                            <td><?php echo htmlspecialchars($provincia['provincia']); ?></td>
                            <td>
                                <button class="btn-eliminar" data-id="<?php echo $provincia['idProvincia']; ?>">
                                    <img src="../../assets/img/boton-eliminar.ico" alt="Eliminar">
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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

    <!-- CDN para la biblioteca SweetAlert2, utilizada para mostrar alertas estilizadas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función para mostrar el formulario de administración de provincias
        function mostrarFormulario() {
            Swal.fire({
                title: '¿Deseas ser redirigido al formulario de registro?',
                text: "Serás redirigido al formulario de registro",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'frm_tb_provincia.php';
                }
            });
        }

        // Enviar formulario con AJAX para agregar provincia
        document.getElementById('form-provincia').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('frm_tb_provincia.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#67f120',
                    });
                });
        });

        // Event listener para los botones de eliminar provincia
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
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`eliminarProvincia.php?id=${id}`, {
                                method: 'GET'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: data.message,
                                        confirmButtonText: 'Aceptar',
                                        confirmButtonColor: '#67f120',
                                    }).then(() => {
                                        // Recargar la página para actualizar el listado
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message,
                                        confirmButtonText: 'Aceptar',
                                        confirmButtonColor: '#67f120',
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error inesperado.',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#67f120',
                                });
                            });
                    }
                });
            });
        });

        // Función para mostrar notificaciones de SweetAlert según el estado de la operación
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');

            if (message === 'provincia_en_uso') {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Advertencia!',
                    text: 'Esta provincia está en uso. La eliminación está prohibida.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            } else if (message === 'provincia_eliminada') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'La provincia ha sido eliminada correctamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            } else if (message === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Hubo un problema al intentar eliminar la provincia. Intente nuevamente.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }
        });
    </script>
</body>