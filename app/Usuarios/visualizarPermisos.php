<?php
// Incluir la conexión a la base de datos
include('../modelos/conexion.php');

// Obtener todos los roles activos
$query_roles = "SELECT * FROM tb_roles WHERE activoRol = 1";
$result_roles = mysqli_query($conection, $query_roles);

// Inicializar variables
$permisos_rol = [];
$rol_id = null;

// Validar si se seleccionó un rol
if (isset($_GET['rol_id']) && !empty($_GET['rol_id'])) {
    $rol_id = intval($_GET['rol_id']);

    // Obtener permisos activos y estado de los permisos
    $query_permisos = "SELECT p.idPermiso, p.descripcionPermiso, 
    IF(rp.permiso_id IS NOT NULL, 1, 0) AS activo
    FROM tb_permisos p
    LEFT JOIN tb_rolesPermisos rp 
    ON p.idPermiso = rp.permiso_id AND rp.rol_id = $rol_id
    WHERE p.activoPermiso = 1";
    $result_permisos = mysqli_query($conection, $query_permisos);
}
if (isset($rol_id)) {
    // Obtener el nombre del rol con el ID proporcionado
    $query = "SELECT nombreRol FROM tb_roles WHERE idRol = '$rol_id'";
    $result_rol = mysqli_query($conection, $query);
    $rol = mysqli_fetch_assoc($result_rol);

    // Obtener el nombre del rol
    $nombreRol = $rol['nombreRol'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar permisos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('../assets/img/background-black.png');
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        p {
            margin-top: 15px;
            font-size: 20px;
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f8f8f8;
            color: #333;
        }

        .switch-container {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch-input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-button {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            border-radius: 20px;
            transition: 0.4s;
        }

        .switch-input:checked+.switch-button {
            background-color: #800080;
        }

        .switch-button-inside {
            position: absolute;
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
        }

        .switch-input:checked+.switch-button .switch-button-inside {
            transform: translateX(20px);
        }

        form {
            text-align: center;
        }

        label {
            font-size: 1.1rem;
            margin-right: 10px;
        }

        select {
            padding: 5px;
            font-size: 1rem;
            width: 200px;
        }

        header {
            background-color: transparent;
            color: #ccc;
            padding: 10px 0;
            text-align: center;
        }

        header .btn-visualizar-permisos,
        .btn-crear-rol {
            margin: 0 10px;
            color: white;
            background-color: #9b59b6;
            border: none;
            padding: 8px 15px;
            text-transform: uppercase;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        header .btn-visualizar-permisos:hover,
        header .btn-crear-rol:hover {
            background-color: #8e44ad;
        }
    </style>
</head>
<?php include 'nav_usuarios.php'; ?>
<body>

    <div class="container">
        <header>
            <button class="btn-visualizar-permisos">Configurar permisos</button>
            <button class="btn-crear-rol">Crear rol administrativo</button>
        </header>
        <h1>Configurar permisos</h1>
        <form action="visualizarPermisos.php" method="GET">
            <label for="rol_id">Seleccione un rol</label>
            <select name="rol_id" id="rol_id" onchange="this.form.submit()">
                <option value="">Seleccione un rol</option>
                <?php while ($rol = mysqli_fetch_assoc($result_roles)): ?>
                    <option value="<?= $rol['idRol'] ?>" <?= ($rol_id == $rol['idRol']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($rol['nombreRol']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if (isset($rol_id)): ?>
            <?php if (isset($rol_id)): ?>
    <p>Permisos asignados al rol <?= htmlspecialchars($nombreRol) ?></p>
<?php endif; ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>Permiso</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($permiso = mysqli_fetch_assoc($result_permisos)): ?>
                        <tr>
                            <td><?= htmlspecialchars($permiso['descripcionPermiso']) ?></td>
                            <td>
                                <label class="switch-container">
                                    <input type="checkbox"
                                        class="switch-input"
                                        data-rol-id="<?= $rol_id ?>"
                                        data-permiso-id="<?= $permiso['idPermiso'] ?>"
                                        <?= $permiso['activo'] ? 'checked' : '' ?>>
                                    <div class="switch-button">
                                        <div class="switch-button-inside"></div>
                                    </div>
                                </label>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Seleccione un rol para ver y modificar sus permisos.</p>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.querySelectorAll('.switch-input').forEach(input => {
                input.addEventListener('change', function() {
                    const rolId = this.dataset.rolId;
                    const permisoId = this.dataset.permisoId;
                    const action = this.checked ? 'activar' : 'desactivar';

                    fetch('actualizarPermisos.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                rolId,
                                permisoId,
                                action
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (action === 'activar') {
                                    Swal.fire({
                                        title: '¡Permiso activado!',
                                        text: 'El permiso se activó correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar',
                                        confirmButtonColor: '#67f120'
                                    });
                                } else if (action === 'desactivar') {
                                    Swal.fire({
                                        title: 'Permiso desactivado',
                                        text: 'El permiso se desactivó correctamente.',
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar',
                                        confirmButtonColor: '#67f120'
                                    });
                                }
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo actualizar el permiso.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Ocurrió un error en la conexión con el servidor.', 'error');
                        });
                });
            });
        </script>
        <script>
            document.querySelector('.btn-visualizar-permisos').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Deseas configurar los permisos?',
                    text: "Serás redirigido a la página de configuración de permisos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6181e7',
                    cancelButtonColor: '#cd4646',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'visualizarPermisos.php';
                    }
                });
            });

            document.querySelector('.btn-crear-rol').addEventListener('click', function() {
                Swal.fire({
                    title: '¿Deseas crear un nuevo rol administrativo?',
                    text: "Serás redirigido a la página de registro de roles administrativos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, crear rol',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    confirmButtonColor: '#6181e7',
                    cancelButtonColor: '#cd4646',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'crearRol.php';
                    }
                });
            });
        </script>
</body>

</html>