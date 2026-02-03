<?php
session_start();
include("../modelos/conexion.php");

// Obtener el ID de la sucursal
$idSucursal = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idSucursal <= 0) {
    die("No se recibió ninguna sucursal.");
}

// Obtener los detalles de la sucursal y la persona jurídica
$query = "SELECT s.*, pj.razonSocial, pj.persona_fisica_id, pf.nombres, pf.apellidos 
          FROM tb_sucursal s
          LEFT JOIN tb_personas_juridicas pj ON s.persona_juridica_id = pj.idPersonaJuridica
          LEFT JOIN tb_personas_fisicas pf ON pj.persona_fisica_id = pf.idPersonaFisica
          WHERE s.idSucursal = ?";
$stmt = $conection->prepare($query);
$stmt->bind_param("i", $idSucursal);
$stmt->execute();
$sucursal = $stmt->get_result()->fetch_assoc();

if (!$sucursal) {
    die("Sucursal no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualización de sucursal</title>
    <!-- Reutilizar Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style_nav_module.css">
    <!-- Bootstrap CSS: Estilos de diseño responsivo y componentes predefinidos. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../assets/img/IconoLog.ico">
    <style>
        body {
            background: url('../assets/img/background-black.png') repeat center center fixed;
        }

        nav {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 10px;
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
            width: 30%;
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

        .fa-eye,
        .fa-eye-slash {
            font-size: 16px;
            line-height: 1.5;
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

        .input-group {
            align-items: center;
            /* Alinea verticalmente los elementos dentro del input-group */
        }

        .input-group-text {
            display: flex;
            align-items: center;
            /* Alinea verticalmente el icono dentro del span */
            padding: 0.375rem 0.75rem;
            /* Ajusta el padding según sea necesario */
            height: auto;
            /* Asegúrate de que el span no tenga una altura fija */
        }

        .form-control {
            padding-right: 38px;
            /* Para evitar que el icono de ojo se superponga al texto */
        }

    </style>
</head>

<body>
    <?php include 'MenuNavegacionSucursales.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Actualizar información de sucursal</h1>

        <form action="recibirModificarSucursal.php" method="post">
            <input type="hidden" name="idSucursal" value="<?php echo htmlspecialchars($idSucursal); ?>">

            <!-- Campo para modificar el nombre de la sucursal -->
            <div class="form-group mb-3">
                <label for="nombreSucursal">Nombre de la sucursal</label>
                <input type="text" name="nombreSucursal" id="nombreSucursal" class="form-control" value="<?php echo htmlspecialchars($sucursal['nombreSucursal']); ?>" maxlength="50" required>
            </div>

            <!-- Campo para seleccionar la persona jurídica -->
            <div class="form-group mb-3">
                <label for="persona_juridica_id">Responsable</label>
                <select name="persona_juridica_id" id="persona_juridica_id" class="form-control" required>
                    <?php
                    // Consultar todas las personas jurídicas
                    $personasJuridicasQuery = "SELECT * FROM tb_personas_juridicas";
                    $personasJuridicasResult = $conection->query($personasJuridicasQuery);

                    while ($personaJuridica = $personasJuridicasResult->fetch_assoc()) {
                        $selected = ($personaJuridica['idPersonaJuridica'] == $sucursal['persona_juridica_id']) ? 'selected' : '';
                        echo "<option value=\"{$personaJuridica['idPersonaJuridica']}\" $selected>{$personaJuridica['razonSocial']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Modificar nombres y apellidos de la persona física asociada -->
            <div class="form-group mb-3">
                <label for="nombres">Nombre</label>
                <input type="text" name="nombres" id="nombres" class="form-control" value="<?php echo htmlspecialchars($sucursal['nombres']); ?>" maxlength="50" required>
            </div>

            <div class="form-group mb-3">
                <label for="apellidos">Apellido</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?php echo htmlspecialchars($sucursal['apellidos']); ?>" maxlength="50" required>
            </div>

            <button type="submit" class="btn custom-btn">Actualizar sucursal</button>
        </form>
    </div>
    <script src="ButtonOnTheRight.js"></script>
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>