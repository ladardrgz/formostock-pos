<?php
header('Content-Type: application/json');

// Conectar a la base de datos
include '../modelos/conexion.php';

$categoria_id = $_POST['categoria_id']; 
$porcentaje_incremento = $_POST['porcentaje_incremento']; 
$response = array();

try {
    // Verificar que los campos no estén vacíos
    if (empty($categoria_id) || empty($porcentaje_incremento)) {
        throw new Exception("Debes seleccionar una categoría y proporcionar un porcentaje de incremento.");
    }

    // Consultar los productos de la categoría seleccionada
    $query = "SELECT idProducto, precioProducto FROM tb_productos WHERE categoria_id = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idProducto = $row['idProducto'];
            $precioActual = $row['precioProducto'];

            // Calcular el nuevo precio
            $nuevoPrecio = $precioActual + ($precioActual * ($porcentaje_incremento / 100));

            // Actualizar el precio en la base de datos
            $updateQuery = "UPDATE tb_productos SET precioProducto = ? WHERE idProducto = ?";
            $updateStmt = $conection->prepare($updateQuery);
            $updateStmt->bind_param("di", $nuevoPrecio, $idProducto);
            $updateStmt->execute();
        }
        $response['success'] = true;
        $response['message'] = "Los precios de los productos en la categoría seleccionada han sido actualizados.";
    } else {
        $response['success'] = false;
        $response['message'] = "No se encontraron productos en la categoría seleccionada.";
    }

    $stmt->close();
    $conection->close();
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
