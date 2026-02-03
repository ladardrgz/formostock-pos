<?php 
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreEstLog'])) {
    $nombreEstLog = mysqli_real_escape_string($conection, $_POST['nombreEstLog']);

    // Verificar si el nombre ya existe en la base de datos
    $queryCheckName = "SELECT COUNT(*) AS count FROM tb_estados_logicos WHERE nombreEstLog = '$nombreEstLog'";
    $resultCheckName = mysqli_query($conection, $queryCheckName);

    if ($resultCheckName) {
        $data = mysqli_fetch_assoc($resultCheckName);
        
        // Si el nombre ya existe, mostrar un mensaje de error
        if ($data['count'] > 0) {
            // Redirigir con un mensaje indicando que el nombre ya está en uso
            header('Location: tb_estados_logicos.php?message=name_in_use');
            exit();
        } else {
            // Si el nombre no está en uso, proceder con la inserción
            $query = "INSERT INTO tb_estados_logicos (nombreEstLog) VALUES ('$nombreEstLog')";

            if (mysqli_query($conection, $query)) {
                // Si la inserción es exitosa, redirigir
                header('Location: tb_estados_logicos.php?message=added');
                exit();
            } else {
                die("Error al agregar el estado lógico: " . mysqli_error($conection));
            }
        }
    } else {
        die("Error al verificar el nombre del estado lógico: " . mysqli_error($conection));
    }
}

// Configuración de paginación y consultas
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) AS total FROM tb_estados_logicos";
$total_result = mysqli_query($conection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$query = "SELECT idEstLog, nombreEstLog FROM tb_estados_logicos LIMIT $limit OFFSET $offset";
$result = mysqli_query($conection, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conection));
}

// Cerrar la conexión
mysqli_close($conection);
?>
