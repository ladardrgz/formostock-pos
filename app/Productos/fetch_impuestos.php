<?php
include '../modelos/conexion.php';

class Impuesto {
    private $conection;

    public function __construct($conection) {
        $this->conection = $conection;
    }

    public function obtenerImpuestos() {
        $sql = "SELECT di.idDetalleImpuesto, ti.nombreImpuesto, di.valorDetalleImpuesto 
                FROM tb_detalle_impuestos di 
                JOIN tb_tipo_impuestos ti ON di.tipo_impuesto_id = ti.idTipoImpuesto";
        $result = mysqli_query($this->conection, $sql);

        $impuestos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $impuestos[] = $row;
        }

        return $impuestos;
    }
}
?>
