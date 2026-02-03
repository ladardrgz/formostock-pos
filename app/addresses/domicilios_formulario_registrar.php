<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de domicilios</title>
    <link rel="stylesheet" href="style_addresses.css">
    <!-- Agregar Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container">
    <div class="caja">
        <h1>Registro de domicilio de usuarios</h1>
        <a href="datos_geograficos_registrar.php" class="btn btn-primary">¿Deseas registrar otros datos?</a>
        <form action="domicilios_recibir_formulario.php" method="post">

            <label for="valorDomicilio">Tipo de domicilio:</label><br>
            <select name="valorDomicilio" class="form-control">
                <option value="Dirección particular">Dirección particular</option>
                <option value="Dirección de trabajo">Dirección laboral</option>
                <option value="Dirección de envío">Dirección de envío</option>
            </select><br><br>

            <label for="persona_fisica_id">Cliente:</label><br>
            <select name="persona_fisica_id" id="idPersonaFisica" class="form-control" required>
                <option value="">Seleccione un cliente:</option>

                <?php
                include '../modelos/conexion.php';

                $query = "SELECT idPersonaFisica, nombres, apellidos FROM tb_personas_fisicas";
                $result = $conection->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idPersonaFisica'] . "'>" . $row['nombres'] . ' ' . $row['apellidos'] . "</option>";
                }
                ?>

            </select><br><br>

            <label for="pais">País:</label><br>
            <select name="pais" id="pais" class="form-control" required>
                <option value="">Seleccione un país</option>
                <?php

                $query = "SELECT idPais, nombrepais FROM tb_paises";
                $result = $conection->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idPais'] . "'>" . $row['nombrepais'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="provincia">Provincia:</label><br>
            <select name="provincia" id="provincia" class="form-control" required>
                <option value="">Seleccione una provincia</option>
            </select><br><br>

            <label for="localidad">Localidad:</label><br>
            <select name="localidad" id="localidad" class="form-control" required>
                <option value="">Seleccione una localidad</option>
            </select><br><br>

            <label for="barrio">Barrio:</label><br>
            <select name="barrio_id" id="barrio" class="form-control" required>
                <option value="">Seleccione un barrio</option>
                <?php
                $query = "SELECT idBarrio, nombreBarrio FROM tb_barrios";
                $result = $conection->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idBarrio'] . "'>" . $row['nombreBarrio'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="descripcionDomicilio">Calle y altura:</label><br>
            <input type="text" id="descripcionDomicilio" name="descripcionDomicilio" class="form-control" required><br>

            <input type="submit" value="Registrar" class="btn btn-primary">
        </form>
    </div>
    <script src="script.js"></script>

    <!-- Agregar Bootstrap JS (opcional, si se requiere funcionalidad adicional de Bootstrap) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
