<?php
include('../../modelos/conexion.php');

// Verificar si hay un mensaje en la URL
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Paginación: configurar el número de marcas por página
$marcasPorPagina = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $marcasPorPagina;

// Consultar la base de datos para obtener el total de marcas
$total_query = "SELECT COUNT(*) as total FROM tb_marcas_productos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_marcas = $total_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_marcas / $marcasPorPagina);

// Consultar las marcas a mostrar en la página actual
$query = "SELECT * FROM tb_marcas_productos LIMIT $marcasPorPagina OFFSET $offset";
$result = mysqli_query($conection, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de marcas</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/IconoLog.ico">
    <link rel="stylesheet" href="../../assets/css/style_nav_module.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
         /* Estilo global */
         html,
        body {
            background-image: url('../../assets/img/background-black.png');
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal */
        #container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Aseguramos que los elementos se alineen hacia arriba */
            min-height: 100vh;
            padding: 20px;
            overflow-y: auto;
        }

        /* Caja de contenido principal */
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
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
        #btn-agregar {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 15px auto;
            padding: 10px;
            background-color: #9b59b6;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        #btn-agregar:hover {
            background-color: #8e44ad;
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
            /* Estilos del botón de administrar */
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
            margin-right: auto; /* Para centrar el botón */
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
</head>
<body>
    <?php include('../nav_productos.php'); ?>
    <div id="container">
        <div class="caja">
            <h3>Catálogo de marcas</h3>
            <button id="btn-administrar" onclick="mostrarFormulario()">
                <img src="../../assets/img/desplegar.png" alt="Icono">
               Nueva categoría
            </button>
            
            <!-- Formulario de nueva marca (oculto por defecto) -->
            <div id="form-agregar" style="display:none;">
                <form action="insertar_tb_marca.php" method="POST">
                    <input type="text" name="nombreMarcaProducto" placeholder="Nombre de la marca" required>
                    <button type="submit">Agregar</button>
                </form>
            </div>
            <p class="aviso"><i class="bi bi-info-circle"></i> Haz clic aquí <img src="../../assets/img/desplegar.png" alt="Icono"> para desplegar el formulario y registrar una nueva marca.</p>
            
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
                        echo "<td>" . htmlspecialchars($row['nombreMarcaProducto']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn-eliminar' data-id='" . $row['idMarcaProducto'] . "'>";
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
  // Mostrar formulario de agregar marca
function mostrarFormulario() {
    var form = document.getElementById('form-agregar');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Eliminar marca con SweetAlert
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
                window.location.href = `eliminar_marca.php?id=${id}`;
            }
        })
    });
});
        // Mostrar mensaje según el parámetro en la URL
        document.addEventListener('DOMContentLoaded', function() {
            const message = '<?php echo $message; ?>';
            if (message === 'added') {
                Swal.fire({
                    title: '¡Marca agregada!',
                    text: 'La nueva marca de producto ha sido agregada con éxito.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                });
            } else if (message === 'deleted') {
                Swal.fire({
                    title: '¡Marca eliminada!',
                    text: 'La marca de producto ha sido eliminada con éxito.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#67f120',
                });
            }
        });
    </script>
</body>
</html>
