<?php
include '../modelos/conexion.php';

class Categoria {
    private $conection;

    public function __construct($conection) {
        $this->conection = $conection;
    }

    public function obtenerCategorias() {
        $sql = "SELECT idCategoriaProducto, nombreCategoriaProducto FROM tb_categorias_productos";
        $result = mysqli_query($this->conection, $sql);

        $categorias = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categorias[] = $row;
        }

        return $categorias;
    }
}
?>
