<?php
// Incluir el archivo de conexión
include('../../modelos/conexion.php');

// Verificar la conexión
if (!$conection) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Verificar si se ha enviado el ID del estado a eliminar
if (isset($_GET['id'])) {
    $idEstLog = (int)$_GET['id'];

    // Verificar las tablas que tienen clave foránea hacia la tabla tb_estados_logicos
    $foreignKeyTablesQuery = "
        SELECT TABLE_NAME, COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_NAME = 'tb_estados_logicos' 
        AND REFERENCED_COLUMN_NAME = 'idEstLog' 
        AND TABLE_SCHEMA = DATABASE();";

    $foreignKeyTablesResult = mysqli_query($conection, $foreignKeyTablesQuery);

    if (!$foreignKeyTablesResult) {
        die("Error al consultar las tablas con clave foránea: " . mysqli_error($conection));
    }

    $tablesInUse = [];
    while ($row = mysqli_fetch_assoc($foreignKeyTablesResult)) {
        // Almacenamos la tabla y el nombre de la columna que hace referencia a `idEstLog`
        $tablesInUse[] = [
            'table' => $row['TABLE_NAME'],
            'column' => $row['COLUMN_NAME']
        ];
    }

    // Comprobar si el estado lógico está siendo referenciado en alguna tabla
    foreach ($tablesInUse as $table) {
        $checkQuery = "SELECT COUNT(*) AS count FROM {$table['table']} WHERE {$table['column']} = $idEstLog";
        $checkResult = mysqli_query($conection, $checkQuery);
        
        $data = mysqli_fetch_assoc($checkResult);
        if ($data['count'] > 0) {
            // Si está en uso, redirigir con el mensaje "estado_en_uso"
            header('Location: tb_estados_logicos.php?message=estado_en_uso');
            exit();
        }
    }

    // Intentar eliminar el estado lógico
    $query = "DELETE FROM tb_estados_logicos WHERE idEstLog = $idEstLog";

    // Ejecutar la consulta de eliminación
    if (mysqli_query($conection, $query)) {
        // Si la eliminación es exitosa, redirigir con mensaje 'deleted'
        header('Location: tb_estados_logicos.php?message=deleted');
        exit();
    } else {
        // Si ocurre un error, capturar el mensaje del error
        // y redirigir con el mensaje de "estado_en_uso" si hay una violación de clave foránea
        $error = mysqli_error($conection);
        
        // Si el error es una violación de la clave foránea
        if (strpos($error, 'FOREIGN KEY') !== false) {
            // Redirigir con el mensaje de "estado_en_uso"
            header('Location: tb_estados_logicos.php?message=estado_en_uso');
            exit();
        } else {
            // Si no es un error de clave foránea, mostramos el error genérico
            die("Error al eliminar el estado lógico: " . $error);
        }
    }
} else {
    // Si no se proporciona un ID, redirigir a la página principal
    header('Location: tb_estados_logicos.php');
    exit();
}

?>
