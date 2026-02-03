<?php
    $descripcionDomicilio = "";
    $barrio_id = "";
    $persona_fisica_id = "";
    $valorDomicilio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcionDomicilio = $_POST["descripcionDomicilio"];
    $barrio_id = $_POST["barrio_id"];
    $persona_fisica_id = $_POST["persona_fisica_id"];
    $valorDomicilio = $_POST["valorDomicilio"]; 
    
    include '../modelos/conexion.php';

    // Insertar datos en la tabla de domicilios
    $query = "INSERT INTO tb_domicilios (descripcionDomicilio, barrio_id) VALUES ('$descripcionDomicilio', '$barrio_id')";
    if ($conection->query($query) === TRUE) {
        // Obtener el ID del domicilio recién insertado
        $idDomicilio = $conection->insert_id;
        
        // Insertar el registro en la tabla de domicilios_personas
        $query_domicilios_personas = "INSERT INTO tb_domicilios_personas (valorDomicilio, persona_fisica_id, domicilio_id) VALUES ('$valorDomicilio', '$persona_fisica_id', '$idDomicilio')";
        if ($conection->query($query_domicilios_personas) === TRUE) {
            // Mensaje de éxito
            echo "El domicilio se registró correctamente.";
        } else {
            // Mensaje de error
            echo "Error al registrar el domicilio de la persona: " . $conection->error;
        }
    } else {
        // Mensaje de error
        echo "Error al registrar el domicilio: " . $conection->error;
    }
}
    $conection->close();
?>
