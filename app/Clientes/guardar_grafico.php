<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['chart'])) {
    $uploadDir = '../charts/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . 'reporte_clientes.png';
    move_uploaded_file($_FILES['chart']['tmp_name'], $filePath);
}
?>
