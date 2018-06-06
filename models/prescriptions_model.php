<?php

class prescriptions_model {

    public $db;
    private $id;
    private $pet;
    private $medicine;
    private $quantity;
    private $observation;
    private $collected;
    private $chronic;

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    function getDb() {
        return $this->db;
    }

    function getId() {
        return $this->id;
    }

    function getPet() {
        return $this->pet;
    }

    function getMedicine() {
        return $this->medicine;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getObservation() {
        return $this->observation;
    }

    function getCollected() {
        return $this->collected;
    }

    function getChronic() {
        return $this->chronic;
    }

    function setDb($db) {
        $this->db = $db;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPet($pet) {
        $this->pet = $pet;
    }

    function setMedicine($medicine) {
        $this->medicine = $medicine;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setObservation($observation) {
        $this->observation = $observation;
    }

    function setCollected($collected) {
        $this->collected = $collected;
    }

    function setChronic($chronic) {
        $this->chronic = $chronic;
    }

    // función para añadir una receta a una mascota
    public function add_prescription() {

        $query = "INSERT INTO recetas (mascota, medicamento, cantidad, observacion, cronico, fechaReceta)
                    VALUES ({$this->pet['id']},{$this->medicine}, {$this->quantity}, '{$this->observation}','{$this->chronic}', NOW())";
                    
        $result = $this->db->query($query);
        if ($this->db->error)
            return "true";
        else {
            return "false";
        }
    }

    // script para generar recetas desde hace un año al día de hoy 
    public function add_prescriptions_script() {

        // id_med es el id del medicamento con el que se generarán recetas
        $id_med = 17;

        $min = 1;
        $max = 100;
        $quantity = rand($min, $max);

        // la fecha de hace un año
        $time = strtotime("-1 year", time());
        $date = date("Y-m-d", $time);

        // la fecha de ahora
        $dateNow = date("Y-m-d");

        while ($date != $dateNow) {
            $query = "INSERT INTO recetas (mascota, medicamento, cantidad, observacion, cronico, fechaReceta)
                    VALUES (6, {$id_med}, {$quantity}, 'obervación prueba del medicamento generado con script', 'n', '{$date}')";

            $result = $this->db->query($query);

            // se suma un dia
            $date = date('Y-m-d', strtotime($date . "+1 days"));
        }


        if ($this->db->error)
            return "true";
        else {
            return "false";
        }
    }

    // función para marcar como recogida una receta pasandole como parámetro el id de la receta
    public function set_collected($id_prescription) {
        $sql = "UPDATE recetas SET recogido = 'y' WHERE id = {$id_prescription} ";

        $consulta = $this->db->query($sql);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

}
