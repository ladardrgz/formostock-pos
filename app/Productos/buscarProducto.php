<?php
include("../modelos/conexion.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conection, $_POST['query']);

    $sql = "SELECT idProducto, descripcionProducto 
            FROM tb_productos 
            WHERE descripcionProducto LIKE '%$query%'";

    $result = mysqli_query($conection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($producto = mysqli_fetch_assoc($result)) {
            echo "<a href='#' class='list-group-item list-group-item-action producto-item' data-id='{$producto['idProducto']}'>
                    {$producto['descripcionProducto']}
    </a>";
        }
    } else {
        echo "<p class='list-group-item'>No se encontraron productos.</p>";
    }
}
