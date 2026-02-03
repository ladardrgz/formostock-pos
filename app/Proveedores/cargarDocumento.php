<?php
include_once '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $idOrdenCompra = $_POST['idOrdenCompra'];
        $fileTmpPath = $_FILES['documento']['tmp_name'];
        $fileName = $_FILES['documento']['name'];
        $fileSize = $_FILES['documento']['size'];
        $fileType = $_FILES['documento']['type'];
        $allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errorMessage = "Error: Solo se permiten archivos PDF, Word o TXT.";
            header("Location: ordenesDeCompra.php?error=" . urlencode($errorMessage));
            exit;
        }

        $uploadFolder = '../uploads/documentos/';
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $newFileName = "orden_{$idOrdenCompra}_" . time() . ".$fileExtension";
        $destinationPath = $uploadFolder . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            $query = "
                UPDATE tb_ordenes_compra 
                SET estado_orden_id = 23 
                WHERE idOrdenCompra = $idOrdenCompra
            ";
            if (mysqli_query($conection, $query)) {
                $successMessage = "Órden de compra correctamente verificada.";
                header("Location: ordenesDeCompra.php?success=" . urlencode($successMessage));
                exit;
            } else {
                $errorMessage = "Error al actualizar la base de datos.";
                header("Location: ordenesDeCompra.php?error=" . urlencode($errorMessage));
                exit;
            }
        } else {
            $errorMessage = "Error al mover el archivo al servidor.";
            header("Location: ordenesDeCompra.php?error=" . urlencode($errorMessage));
            exit;
        }
    } else {
        $errorMessage = "No se cargó ningún archivo.";
        header("Location: ordenesDeCompra.php?error=" . urlencode($errorMessage));
        exit;
    }
} else {
    header("Location: ordenesDeCompra.php");
    exit;
}
