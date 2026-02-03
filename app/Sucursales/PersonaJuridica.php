<?php
class PersonaJuridica {
    private $conn;
    private $table_name = "tb_personas_juridicas";

    public $id;
    public $razonSocial;
    public $persona_fisica_id;
    public $estado_persona_juridica_id = 1; // Estado "activo" por defecto

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para insertar persona jurídica
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (razonSocial, persona_fisica_id, estado_persona_juridica_id)
                  VALUES (:razonSocial, :persona_fisica_id, :estado_persona_juridica_id)";

        $stmt = $this->conn->prepare($query);

        // Asignar valores
        $stmt->bindParam(':razonSocial', $this->razonSocial);
        $stmt->bindParam(':persona_fisica_id', $this->persona_fisica_id);
        $stmt->bindParam(':estado_persona_juridica_id', $this->estado_persona_juridica_id);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
}
?>
