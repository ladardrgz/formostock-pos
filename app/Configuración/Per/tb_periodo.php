<?php
include('../../modelos/conexion.php');

// Verificar si hay un mensaje en la URL
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Paginación: configurar el número de períodos por página
$periodosPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $periodosPorPagina;

// Consultar la base de datos para obtener el total de períodos
$total_query = "SELECT COUNT(*) as total FROM tb_periodos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_periodos = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_periodos / $periodosPorPagina);

// Consultar los períodos a mostrar en la página actual
$query = "SELECT p.idPeriodo, p.nombrePeriodo, p.fechaInicioPeriodo, p.fechaFinPeriodo, p.añoPeriodo, e.nombreEstLog 
          FROM tb_periodos p
          JOIN tb_estados_logicos e ON p.estado_periodo_id = e.idEstLog
          LIMIT $periodosPorPagina OFFSET $offset";
$result = mysqli_query($conection, $query);

// Consultar todos los estados lógicos para usarlos en el formulario
$estados_query = "SELECT * FROM tb_estados_logicos";
$estados_result = mysqli_query($conection, $estados_query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de períodos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="../../assets/img/IconoLog.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <style>
        html,
        body {
            background-image: url('../../assets/img/background-black.png');
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        nav {
            margin-bottom: 20px;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 20px;
            min-height: 100vh;
            position: relative;
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
            align-items: center;
        }

        h1,
        h3 {
            text-align: center;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
            min-height: 200px;
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
            width: 100%;
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

        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #9b59b6;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }

        .aviso img {
            width: 20px;
            height: 20px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Eventos de períodos</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/desplegar.png" alt="Icono">
                Agregar nuevo período
            </button>

            <!-- Formulario de nuevo período (oculto por defecto) -->
            <div id="form-agregar" style="display:none;">
                <form action="insertar_periodo.php" method="POST">
                    <div class="form-group">
                        <label for="nombrePeriodo">Nombre del período</label>
                        <input type="text" id="nombrePeriodo" name="nombrePeriodo" maxlength="100" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="fechaInicioPeriodo">Fecha de inicio</label>
                        <input type="date" id="fechaInicioPeriodo" name="fechaInicioPeriodo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="fechaFinPeriodo">Fecha de fin</label>
                        <input type="date" id="fechaFinPeriodo" name="fechaFinPeriodo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="añoPeriodo">Año</label>
                        <input type="number" id="añoPeriodo" name="añoPeriodo" min="2000" max="2100" class="form-control" required>
                    </div>

                    <input type="hidden" name="estado_periodo_id" value="1" id="estado_periodo_id_hidden">


                    <button type="submit">Agregar período</button>
                </form>
            </div>

            <p class="aviso"><i class="bi bi-info-circle"></i> Haz clic aquí <img src="../../assets/img/desplegar.png" alt="Icono"> para desplegar el formulario y registrar un nuevo período.</p>

            <table>
                <thead>
                    <tr>
                        <th>Nombre del período</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de fin</th>
                        <th>Año</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los resultados en una tabla
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombrePeriodo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fechaInicioPeriodo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fechaFinPeriodo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['añoPeriodo']) . "</td>";
                        echo "<td>";
                        echo "<label class='switch'>";
                        echo "<input type='checkbox' class='estado-toggle' data-id='" . $row['idPeriodo'] . "'" . ($row['nombreEstLog'] === 'Activo' ? ' checked' : '') . ">";
                        echo "<span class='slider round'></span>";
                        echo "</label>";
                        echo "</td>";
                        echo "<td>";
                        echo "<button class='btn-eliminar' data-id='" . $row['idPeriodo'] . "'>";
                        echo "<img src='../../assets/img/boton-eliminar.ico' alt='Eliminar'>";
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el mensaje pasado desde PHP a través de la URL
        const message = '<?php echo isset($_GET['message']) ? $_GET['message'] : ''; ?>';

        // Mostrar los mensajes de éxito o error según el valor de 'message'
        if (message === 'added') {
            Swal.fire({
                title: '¡Período agregado!',
                text: 'El nuevo período ha sido agregado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'deleted') {
            Swal.fire({
                title: '¡Período eliminado!',
                text: 'El período ha sido eliminado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_name_exists') {
            Swal.fire({
                title: '¡Error!',
                text: 'Ya existe un período con ese nombre. Por favor, elige otro.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_deleting') {
            Swal.fire({
                title: '¡Error al eliminar!',
                text: 'No se pudo eliminar el período porque está en uso.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_updating_status') {
            Swal.fire({
                title: '¡Error!',
                text: 'Hubo un error al intentar actualizar el estado.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_inserting') {
            Swal.fire({
                title: '¡Error!',
                text: 'Hubo un problema al agregar el nuevo período. Intenta nuevamente.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        }
    });

    // Función para mostrar el formulario de agregar
    function mostrarFormulario() {
        const formAgregar = document.getElementById('form-agregar');
        formAgregar.style.display = formAgregar.style.display === 'none' ? 'block' : 'none';
    }

    // Función para confirmar la eliminación de un período
    document.querySelectorAll('.btn-eliminar').forEach(function(button) {
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
                    window.location.href = `eliminarPeriodo.php?id=${id}`;
                }
            });
        });
    });

    // Asignar evento a los botones o interruptores de estado
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.estado-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const nuevoEstado = this.checked ? 'activo' : 'inactivo';

                // Enviar la solicitud AJAX para cambiar el estado
                fetch(`cambiarEstado.php?id=${id}&estado=${nuevoEstado}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Estado actualizado!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#67f120',
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#67f120',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al intentar actualizar el estado.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#67f120',
                        });
                    });
            });
        });
    });
</script>


</body>

</html>