<?php

class medicines_model {

    public  $db;
    private $categories;

    private $id;
    private $name;

    public function __construct() {
        $this->db = Conectar::conexion();
        $this->categories = array();
    }

    // Función que devuelve todos los productos
    public function get_all_categories() {
        $query = "SELECT * FROM categoria_medicamento";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->categories[] = $filas;
        }
        return $this->categories;
    }


}
