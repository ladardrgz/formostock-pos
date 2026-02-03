<?php
session_start(); // Iniciar la sesión

include_once '../modelos/conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se ha enviado una sucursal seleccionada
if (isset($_POST['idSucursal'])) {
    $sucursal_id = (int)$_POST['idSucursal']; // Obtener el ID de la sucursal seleccionada
    $_SESSION['sucursal_id'] = $sucursal_id; // Guardar la sucursal en la sesión

    // Obtener el nombre de la sucursal seleccionada para mostrar un mensaje de éxito
    $query = "SELECT nombreSucursal FROM tb_sucursal WHERE idSucursal = $sucursal_id";
    $result = mysqli_query($conection, $query);

    // Manejo de errores en la consulta
    if (!$result) {
        die('Error en la consulta de sucursal: ' . mysqli_error($conection));
    }

    $sucursal = mysqli_fetch_assoc($result);
    $_SESSION['mensaje'] = "Has seleccionado la sucursal: " . $sucursal['nombreSucursal'];
}

// Consulta para obtener la lista de sucursales
$query_sucursales = "SELECT idSucursal, nombreSucursal FROM tb_sucursal";
$result_sucursales = mysqli_query($conection, $query_sucursales);

// Manejo de errores en la consulta
if (!$result_sucursales) {
    die('Error en la consulta de sucursales: ' . mysqli_error($conection));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_productos_vw.css">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
</head>
<body>
<?php include 'MenuNavegacionProductos.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Acceso a inventario por sucursal</h1>

        <!-- Mostrar mensaje si existe -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success" role="alert">
                <?php 
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para seleccionar la sucursal -->
        <form method="POST" action="guardarSucursalSession.php">
            <p>Seleccione la sucursal correspondiente en la que desea visualizar el inventario de productos</p>
            <div class="form-group mb-3">
                <label for="idSucursal" class="form-label">Elige una sucursal:</label>
                <select id="idSucursal" name="idSucursal" class="form-select" required>
                    <option value="">Selecciona una sucursal</option>
                    <?php while ($sucursal = mysqli_fetch_assoc($result_sucursales)): ?>
                        <option value="<?php echo $sucursal['idSucursal']; ?>">
                            <?php echo $sucursal['nombreSucursal']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <!-- Mostrar sucursal actual si está seleccionada -->
        <?php if (isset($_SESSION['sucursal_id'])): ?>
            <div class="alert alert-info mt-3">
                <strong>Sucursal seleccionada:</strong>
                <?php 
                // Obtener el nombre de la sucursal seleccionada
                $sucursal_id = $_SESSION['sucursal_id'];
                $query = "SELECT nombreSucursal FROM tb_sucursal WHERE idSucursal = $sucursal_id";
                $result = mysqli_query($conection, $query);
                
                // Manejo de errores en la consulta
                if (!$result) {
                    die('Error en la consulta de sucursal: ' . mysqli_error($conection));
                }

                $sucursal = mysqli_fetch_assoc($result);
                echo $sucursal['nombreSucursal'];
                ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="ButtonOnTheRight.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
