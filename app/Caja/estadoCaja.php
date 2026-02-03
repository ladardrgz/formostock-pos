<?php
class EstadoCaja {
    private $conection;

    public function __construct($conection) {
        $this->conection = $conection;
    }

    public function obtenerEstados() {
        // Consulta para obtener todos los estados de caja
        $query = "SELECT * FROM tb_estados_logicos";
        
        // Ejecutar la consulta
        $result = mysqli_query($this->conection, $query);
        
        // Inicializar el array de estados
        $estados = [];

        // Verificar si la consulta fue exitosa
        if ($result) {
            // Obtener los datos de los estados
            while ($row = mysqli_fetch_assoc($result)) {
                $estados[] = $row;
            }
        } else {
            // Manejar el error si la consulta falla
            error_log("Error en la consulta: " . mysqli_error($this->conection));
        }

        return $estados;
    }
}
?>
