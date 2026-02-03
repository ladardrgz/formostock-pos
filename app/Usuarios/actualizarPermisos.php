<?php
include('../modelos/conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['rolId'], $data['permisoId'], $data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$rolId = intval($data['rolId']);
$permisoId = intval($data['permisoId']);
$action = $data['action'];

if ($action === 'activar') {
    $query = "INSERT INTO tb_rolesPermisos (rol_id, permiso_id) VALUES ($rolId, $permisoId)
    ON DUPLICATE KEY UPDATE permiso_id = permiso_id";
} elseif ($action === 'desactivar') {
    $query = "DELETE FROM tb_rolesPermisos WHERE rol_id = $rolId AND permiso_id = $permisoId";
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}

if (mysqli_query($conection, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta']);
}
