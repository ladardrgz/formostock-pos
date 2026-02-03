<?php
include_once '../modelos/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $idFactura = $_POST['idFactura']; // Cambiado para manejar facturas
        $fileTmpPath = $_FILES['documento']['tmp_name'];
        $fileName = $_FILES['documento']['name'];
        $fileSize = $_FILES['documento']['size'];
        $fileType = $_FILES['documento']['type'];
        $allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validar extensión del archivo
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errorMessage = "Error: Solo se permiten archivos PDF, Word o TXT.";
            header("Location: dashboardVentas.php?error=" . urlencode($errorMessage));
            exit;
        }

        // Crear directorio si no existe
        $uploadFolder = '../uploads/documentos/';
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        // Crear un nuevo nombre de archivo único
        $newFileName = "factura_{$idFactura}_" . time() . ".$fileExtension";
        $destinationPath = $uploadFolder . $newFileName;

        // Mover archivo al servidor
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            // Actualizar el estado de la factura en la base de datos
            $query = "
                UPDATE tb_factura_cabecera
                SET estado_factura_id = 3 
                WHERE idFaCab = $idFactura
            ";
            if (mysqli_query($conection, $query)) {
                $successMessage = "Factura correctamente actualizada y documento cargado.";
                header("Location: dashboardVentas.php?success=" . urlencode($successMessage));
                exit;
            } else {
                $errorMessage = "Error al actualizar la base de datos.";
                header("Location: dashboardVentas.php?error=" . urlencode($errorMessage));
                exit;
            }
        } else {
            $errorMessage = "Error al mover el archivo al servidor.";
            header("Location: dashboardVentas.php?error=" . urlencode($errorMessage));
            exit;
        }
    } else {
        $errorMessage = "No se cargó ningún archivo.";
        header("Location: dashboardVentas.php?error=" . urlencode($errorMessage));
        exit;
    }
} else {
    header("Location: dashboardVentas.php");
    exit;
}
