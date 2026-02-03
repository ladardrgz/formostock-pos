<?php
include('../../modelos/conexion.php');

// Verificar si hay un mensaje en la URL
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Paginación: configurar el número de impuestos por página
$impuestosPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $impuestosPorPagina;

// Consultar la base de datos para obtener el total de impuestos
$total_query = "SELECT COUNT(*) as total FROM tb_detalle_impuestos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_impuestos = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_impuestos / $impuestosPorPagina);

// Consultar los impuestos a mostrar en la página actual
$query = "SELECT d.idDetalleImpuesto, d.valorDetalleImpuesto, t.nombreImpuesto 
          FROM tb_detalle_impuestos d 
          JOIN tb_tipo_impuestos t ON d.tipo_impuesto_id = t.idTipoImpuesto
          LIMIT $impuestosPorPagina OFFSET $offset";
$result = mysqli_query($conection, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de impuestos</title>
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="../../assets/img/IconoLog.ico" type="image/x-icon">
</head>
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

    .aviso img {
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }
</style>

<body>
    <?php include('../nav_configuracion.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Control de impuestos</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/desplegar.png" alt="Icono">
                Agregar nuevo impuesto
            </button>

            <!-- Formulario de nuevo impuesto (oculto por defecto) -->
            <div id="form-agregar" style="display:none;">
                <form action="insertar_impuesto.php" method="POST">
                    <label for="nombreImpuesto">Nombre del impuesto:</label>
                    <input type="text" id="nombreImpuesto" name="nombreImpuesto" maxlength="50" class="input-text" required>

                    <label for="valorDetalleImpuesto">Valor del impuesto (%):</label>
                    <input type="number" step="0.01" id="valorDetalleImpuesto" name="valorDetalleImpuesto" min="0" max="999.99" class="input-number valor-input" placeholder="0.00" required>

                    <button type="submit" id="btn-agregar">Agregar impuesto</button>
                </form>
            </div>

            <p class="aviso"><i class="bi bi-info-circle"></i> Haz clic aquí <img src="../../assets/img/desplegar.png" alt="Icono"> para desplegar el formulario y registrar un nuevo impuesto.</p>

            <table>
                <thead>
                    <tr>
                        <th>Nombre del impuesto</th>
                        <th>Valor (%)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los resultados en una tabla
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombreImpuesto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['valorDetalleImpuesto']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn-eliminar' data-id='" . $row['idDetalleImpuesto'] . "'>";
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
        const message = '<?php echo isset($_GET['message']) ? $_GET['message'] : ''; ?>'; // Obtener mensaje desde la URL
        
        // Verificar el mensaje y mostrar el correspondiente
        if (message === 'added') {
            Swal.fire({
                title: '¡Impuesto agregado!',
                text: 'El nuevo impuesto ha sido agregado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'deleted') {
            Swal.fire({
                title: '¡Impuesto eliminado!',
                text: 'El impuesto ha sido eliminado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_in_use') {
            Swal.fire({
                title: '¡Error!',
                text: 'No se puede eliminar este impuesto porque está en uso.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        } else if (message === 'error_name_exists') {
            Swal.fire({
                title: '¡Error!',
                text: 'Ya existe un impuesto con ese nombre. Por favor, elige otro.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#67f120',
            });
        }
    });

    // Mostrar formulario de agregar impuesto
    function mostrarFormulario() {
        var form = document.getElementById('form-agregar');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    }

    // Eliminar impuesto con SweetAlert
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
                    window.location.href = `eliminar_impuesto.php?id=${id}`;
                }
            });
        });
    });
</script>


</body>

</html>