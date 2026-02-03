<?php
include('manage_states.php');

// Verificar si hay un mensaje en la URL
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de estados</title>
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="../../assets/img/IconoLog.ico" type="image/x-icon">
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
            /* Mantener margen inferior en el nav */
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Asegura que el formulario se mantenga en la parte superior */
            margin-top: 20px;
            /* Margen superior de 20px respecto al nav */
            min-height: 100vh;
            position: relative;
            /* Asegura que el contenedor esté posicionado correctamente */
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
            /* Reducir el margen para un espaciado más pequeño */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            /* Reducir margen superior en la tabla */
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
            min-height: 200px;
            /* Mantener una altura mínima para la tabla */
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            /* Reducir padding en celdas */
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
            margin-top: 10px;
            /* Reducir margen superior en la paginación */
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 8px 12px;
            /* Ajustar el tamaño de la paginación */
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

        #btn-agregar {
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

        #btn-agregar:hover {
            background-color: #8e44ad;
        }

        #form-agregar {
            margin-top: 10px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
        .aviso img {
            width: 20px;
            height: 20px;
            vertical-align: middle;
        }

        #btn-agregar img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Administración de estados</h3>
            <button id="btn-agregar" onclick="mostrarFormulario()">
                <img src="../../assets/img/desplegar.png" alt="Icono">
                Agregar nuevo estado
            </button>

            <!-- Formulario de nuevo estado (oculto por defecto) -->
            <div id="form-agregar" style="display:none;">
                <form action="tb_estados_logicos.php" method="POST">
                    <input type="text" name="nombreEstLog" placeholder="Nombre" required>
                    <button type="submit">Agregar</button>
                </form>
            </div>

            <p class="aviso"><i class="bi bi-info-circle"></i> Haz clic aquí <img src="../../assets/img/desplegar.png" alt="Icono"> para desplegar el formulario y registrar un nuevo estado lógico.</p>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los resultados en una tabla
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombreEstLog']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn-eliminar' data-id='" . $row['idEstLog'] . "'>";
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
    // Mostrar mensaje según el parámetro en la URL
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si existe un mensaje en la variable PHP
        const message = '<?php echo isset($_GET['message']) ? $_GET['message'] : ""; ?>';

        // Condicional para verificar el tipo de mensaje y mostrar la alerta correspondiente
        if (message === 'added') {
            Swal.fire({
                title: '¡Estado agregado!',
                text: 'El nuevo estado lógico ha sido agregado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            }).then(() => {
                // Después de que el usuario presione "Aceptar", redirigir a la página sin el mensaje
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (message === 'deleted') {
            Swal.fire({
                title: '¡Estado eliminado!',
                text: 'El estado lógico ha sido eliminado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            }).then(() => {
                // Después de que el usuario presione "Aceptar", redirigir a la página sin el mensaje
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (message === 'estado_en_uso') {
            Swal.fire({
                title: '¡No se puede eliminar!',
                text: 'Este estado lógico está en uso y no puede ser eliminado.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            }).then(() => {
                // Después de que el usuario presione "Aceptar", redirigir a la página sin el mensaje
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (message === 'name_in_use') {
            Swal.fire({
                title: '¡Nombre en uso!',
                text: 'Este nombre de estado lógico ya está en uso. Por favor elige otro nombre.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            }).then(() => {
                // Después de que el usuario presione "Aceptar", redirigir a la página sin el mensaje
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    });

    // Función para mostrar u ocultar el formulario de agregar nuevo estado lógico
    function mostrarFormulario() {
        const form = document.getElementById('form-agregar');
        // Alternar entre mostrar y ocultar el formulario
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    }

    // Eliminar estado con confirmación
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id'); // Obtener el ID del estado lógico a eliminar

            // Mostrar SweetAlert con opciones de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // Si el usuario confirma la eliminación
                if (result.isConfirmed) {
                    // Redirigir a la URL de eliminación pasando el ID como parámetro
                    window.location.href = `delete_state.php?id=${id}`;
                }
            });
        });
    });
</script>
