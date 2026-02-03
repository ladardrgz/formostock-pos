<?php
include '../modelos/conexion.php';

class Marca {
    private $conection;

    public function __construct($conection) {
        $this->conection = $conection;
    }

    public function obtenerMarcas() {
        $sql = "SELECT idMarcaProducto, nombreMarcaProducto FROM tb_marcas_productos";
        $result = mysqli_query($this->conection, $sql);

        $marcas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $marcas[] = $row;
        }

        return $marcas;
    }
}
?>