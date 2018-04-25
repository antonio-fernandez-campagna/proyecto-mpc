<?php

class medicies_model {

    public $db;
    private $medicies;
    
    private $id;
    private $name;
    private $stock;
    private $price;
    private $sponsored;
    private $shortDescription;
    private $longDescription;
    private $brand;
    private $category;

    public function __construct() {
        $this->db = Conectar::conexion();
        $this->medicies = array();
    }

    // Función que devuelve todos los productos
    public function get_all_products() {
        $query = "SELECT  from product;";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->products[] = $filas;
        }
        return $this->products;
    }


}

