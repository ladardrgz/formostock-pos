<?php
include_once '../modelos/conexion.php';
session_start();

// Verificar el rol del usuario
$userRole = $_SESSION['rol_id'];

$idCliente = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idCliente > 0) {
    $query = "
    SELECT p.idPersonaFisica, p.nombres, p.apellidos, p.fechaNacimiento, p.sexo, 
    d.valorDocumento, e.nombreEstLog, 
    dc.idDetalleContacto, dc.valorDetalleContacto, tc.nombreTipoContacto
    FROM tb_clientes c
    INNER JOIN tb_personas_fisicas p ON c.persona_fisica_id = p.idPersonaFisica
    INNER JOIN tb_detalle_documento d ON p.detalle_documento_id = d.idDetalleDocumento
    INNER JOIN tb_estados_logicos e ON p.estado_persona_id = e.idEstLog
    LEFT JOIN tb_detalle_contacto dc ON p.detalle_contacto_id = dc.idDetalleContacto
    LEFT JOIN tb_tipo_contacto tc ON dc.tipo_contacto_id = tc.idTipoContacto
    WHERE c.idCliente = $idCliente";
    $result = mysqli_query($conection, $query);
    $cliente = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente</title>
    <!-- Incluir Bootstrap CSS desde un CDN para estilos responsivos y componentes predefinidos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados del usuario -->
    <link rel="stylesheet" href="../assets/css/#.css">

    <!-- Estilos personalizados del módulo de navegación -->
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">

    <!-- Incluir SweetAlert2 CSS desde un CDN para alertas estilizadas y modales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Favicon: Icono que se muestra en la pestaña del navegador -->
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">

    <!-- Incluir SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background: url('../assets/img/background-black.png') no-repeat center center fixed;
        }

        nav {
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .btn {
            width: 100%;
        }

        .custom-btn {
            background-color: #7b5095;
            color: white;
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .custom-btn:hover {
            background-color: #693e77;
            color: #fff;
        }

        .text-center {
            text-align: center;
            margin-bottom: 15px;
        }

        .errorValidacion {
            color: red;
            font-size: 15px;
            margin-top: 15px;
            display: block;
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .form-control[readonly] {
            background: linear-gradient(135deg, rgba(240, 240, 240, 0.8), rgba(220, 220, 220, 0.5));
            color: #555;
            cursor: not-allowed;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <?php include('nav_clientes.php'); ?>
    <div class="container mt-4">

        <?php if ($cliente): ?>
            <form method="POST" action="procesarEdicionCliente.php">
                <h2>Editar cliente</h2>
                <input type="hidden" name="idCliente" value="<?php echo htmlspecialchars($idCliente); ?>">

                <div class="form-group">
                    <label for="nombres" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($cliente['nombres']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($cliente['apellidos']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo htmlspecialchars($cliente['fechaNacimiento']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="sexo" class="form-label">Género</label>
                    <select id="sexo" name="sexo" class="form-select" required>
                        <option value="Masculino" <?php echo ($cliente['sexo'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($cliente['sexo'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        <option value="No binario" <?php echo ($cliente['sexo'] == 'No binario') ? 'selected' : ''; ?>>No binario</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valorDocumento" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="valorDocumento" name="valorDocumento" value="<?php echo htmlspecialchars($cliente['valorDocumento']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="tipo_contacto" class="form-label">Tipo de contacto</label>
                    <select id="tipo_contacto" name="tipo_contacto" class="form-select" required>
                        <?php
                        // Obtener tipos de contacto
                        $tipoContactoQuery = "SELECT * FROM tb_tipo_contacto";
                        $tipoContactoResult = mysqli_query($conection, $tipoContactoQuery);
                        while ($tipo = mysqli_fetch_assoc($tipoContactoResult)) {
                            $selected = ($cliente['nombreTipoContacto'] == $tipo['nombreTipoContacto']) ? 'selected' : '';
                            echo "<option value='{$tipo['idTipoContacto']}' $selected>{$tipo['nombreTipoContacto']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valor_contacto" class="form-label">Valor de contacto</label>
                    <input type="text" class="form-control" id="valor_contacto" name="valor_contacto" value="<?php echo htmlspecialchars($cliente['valorDetalleContacto']); ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn custom-btn">Guardar cambios</button>
                </div>
            </form>
        <?php else: ?>
            <p>Cliente no encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- jQuery (necesario para Bootstrap's JavaScript) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>