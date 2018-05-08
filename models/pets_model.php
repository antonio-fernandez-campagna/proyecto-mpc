<?php

class pets_model {

    public $db;
    private $species;
    private $petId;
    private $id;
    private $specie;
    private $sex;
    private $birthDate;
    private $dniProp;
    private $tlfProp;
    private $chip;
    private $name;
    private $weight;

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    function getId() {
        return $this->id;
    }

    function getSpecie() {
        return $this->specie;
    }

    function getSex() {
        return $this->sex;
    }

    function getBirthDate() {
        return $this->birthDate;
    }

    function getDniProp() {
        return $this->dniProp;
    }

    function getTlfProp() {
        return $this->tlfProp;
    }

    function getChip() {
        return $this->chip;
    }

    function getName() {
        return $this->name;
    }

    function getWeight() {
        return $this->weight;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSpecie($specie) {
        $this->specie = $specie;
    }

    function setSex($sex) {
        $this->sex = $sex;
    }

    function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    function setDniProp($dniProp) {
        $this->dniProp = $dniProp;
    }

    function setTlfProp($tlfProp) {
        $this->tlfProp = $tlfProp;
    }

    function setChip($chip) {
        $this->chip = $chip;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setWeight($weight) {
        $this->weight = $weight;
    }

    // Función que devuelve los medicamentos por su nombre
    public function get_species() {
        $query = "SELECT * FROM animales";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    public function add_pet() {
        $query = "INSERT INTO mascota (especie, sexo, fechanac, dni_propietario, chip, nombre, peso)
                    VALUES ({$this->specie},'{$this->sex}', '{$this->birthDate}', '{$this->dniProp}',{$this->chip},
                    '{$this->name}',{$this->weight})";

        $result = $this->db->query($query);
        if ($this->db->error)
            return "true";
        else {
            return "false";
        }
    }

    public function get_pet_dni($dni) {
        $query = "select m.*, a.especie, r.medicamento, r.observacion from mascota m, recetas r, animales a where m.dni_propietario = '{$dni}' and "
                . "a.especie = m.especie and r.cronico = 'y'";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    public function get_pet_id($chip) {
        $query = "SELECT id FROM mascota where chip = '{$chip}'";

        $consulta = $this->db->query($query);
        $filas = $consulta->fetch_assoc();
        $this->petId = $filas;

        return $this->petId;
    }

}
