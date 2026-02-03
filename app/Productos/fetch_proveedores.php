<?php
class Proveedor {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerProveedores() {
        $sql = "
            SELECT tb_proveedores.idProveedor, tb_personas_juridicas.razonSocial 
            FROM tb_proveedores 
            JOIN tb_personas_juridicas ON tb_proveedores.persona_juridica_id = tb_personas_juridicas.idPersonaJuridica 
            WHERE tb_personas_juridicas.estado_persona_juridica_id = 1
        ";
        $resultado = mysqli_query($this->conexion, $sql);
        
        $proveedores = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            $proveedores[] = $row;
        }
        return $proveedores;
    }
}
