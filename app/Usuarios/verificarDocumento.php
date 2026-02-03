<?php
include '../modelos/conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$valorDocumento = $data['valorDocumento'];
$tipoDocumentoId = $data['tipoDocumentoId'];

$sql = "SELECT COUNT(*) as total FROM tb_detalle_documento WHERE valorDocumento = ? AND tipo_documento_id = ?";
$stmt = $conection->prepare($sql);
$stmt->bind_param("si", $valorDocumento, $tipoDocumentoId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
    echo json_encode(['existe' => true]);
} else {
    echo json_encode(['existe' => false]);
}
?>
