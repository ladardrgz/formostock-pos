<?php
include_once '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sucursal_id = (int) $_POST['sucursal_id'];
    $producto_id = (int) $_POST['producto_id'];
    $cantidad = (int) $_POST['cantidad'];

    $query = "SELECT stockSucursal FROM tb_inventario_sucursal 
              WHERE sucursal_id = ? AND producto_id = ?";
    $stmt = $conection->prepare($query);
    $stmt->bind_param("ii", $sucursal_id, $producto_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $updateQuery = "UPDATE tb_inventario_sucursal 
                        SET stockSucursal = stockSucursal + ?
                        WHERE sucursal_id = ? AND producto_id = ?";
        $updateStmt = $conection->prepare($updateQuery);
        $updateStmt->bind_param("iii", $cantidad, $sucursal_id, $producto_id);

        if ($updateStmt->execute()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Stock incrementado correctamente.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'incrementar_stock.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al incrementar el stock.',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El producto no existe en la sucursal especificada.',
                    confirmButtonText: 'OK'
                });
              </script>";
    }

    $stmt->close();
    $conection->close();
}
?>
