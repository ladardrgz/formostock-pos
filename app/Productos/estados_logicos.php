<?php
include '../modelos/conexion.php';

class EstadoLogico {
    private $conection;

    public function __construct($conection) {
        $this->conection = $conection;
    }

    public function obtenerEstadosLogicos() {
        $sql = "SELECT idEstLog, nombreEstLog FROM tb_estados_logicos";
        $result = mysqli_query($this->conection, $sql);

        $estadosLogicos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $estadosLogicos[] = $row;
        }

        return $estadosLogicos;
    }
}
?>
