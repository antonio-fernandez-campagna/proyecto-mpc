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
        $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where m.dni_propietario = '{$dni}}' and a.id = m.especie ";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    public function get_pet_chip($chip) {
        $query = "select DISTINCT m.*, a.especie, r.medicamento, r.observacion, r.cronico, med_nom.nombre as nombre_medicamento , TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age "
                . "from mascota m, recetas r, animales a, medicamento_nombre med_nom where a.id = m.especie and med_nom.id = r.medicamento and m.chip = {$chip} and r.cronico = 'y'";


        $consulta = $this->db->query($query);

        if ($consulta->num_rows == 0) {


          $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where a.id = m.especie and m.chip = {$chip}";
                  $consulta = $this->db->query($query);

                  while ($filas = $consulta->fetch_assoc()) {
                      $this->species[] = $filas;
                  }

                  return $this->species;

        } else {

          while ($filas = $consulta->fetch_assoc()) {
              $this->species[] = $filas;
          }
          return $this->species;
        }
    }

    public function get_pet_from_id($id) {
        $query = "select DISTINCT m.*, a.especie, r.medicamento, r.observacion, r.cronico, med_nom.nombre as nombre_medicamento , TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age "
                . "from mascota m, recetas r, animales a, medicamento_nombre med_nom where a.id = m.especie and med_nom.id = r.medicamento and m.id = {$id} and r.cronico = 'y'";

        $consulta = $this->db->query($query);

        //echo "<pre>".print_r($consulta, 1)."</pre>";


        if ($consulta->num_rows == 0) {


          $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where a.id = m.especie and m.id = {$id}";

          $consulta = $this->db->query($query);

          while ($filas = $consulta->fetch_assoc()) {
                $this->species[] = $filas;
              }

          return $this->species;

        } else {

          while ($filas = $consulta->fetch_assoc()) {
              $this->species[] = $filas;
          }
          return $this->species;
        }
    }

    public function get_pet_id($chip) {
        $query = "SELECT id FROM mascota where chip = '{$chip}'";

        $consulta = $this->db->query($query);
        $filas = $consulta->fetch_assoc();
        $this->petId = $filas;

        return $this->petId;
    }

    public function set_pet($id_pet) {
        $sql = "UPDATE mascota SET nombre= '{$this->name}', peso={$this->weight}, dni_propietario='{$this->dniProp}' WHERE id = {$id_pet} ";

        $consulta = $this->db->query($sql);

        if ($this->db->error)
            return "$consulta<br>{$this->db->error}";
        else {
            return false;
        }
    }

    public function get_info_pet_dni($dni) {

      if (!empty($dni)) {
        $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where m.dni_propietario = '{$dni}' and a.id = m.especie";
      } else {
        $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where a.id = m.especie and m.chip = {$chip}";
      }

      $consulta = $this->db->query($query);
      while ($filas = $consulta->fetch_assoc()) {
          $this->species[] = $filas;
      }
      return $this->species;
    }

    public function get_info_pet_chip($chip) {

      $query = "select DISTINCT m.*, a.especie, r.medicamento, r.observacion, r.cronico, med_nom.nombre as nombre_medicamento , TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age "
              . "from mascota m, recetas r, animales a, medicamento_nombre med_nom where a.id = m.especie and med_nom.id = r.medicamento and m.chip = {$chip} and r.cronico = 'y'";

      $consulta = $this->db->query($query);


      if ($consulta->num_rows == 0) {

        $query = "select DISTINCT m.*, a.especie, TIMESTAMPDIFF(YEAR, m.fechanac, CURDATE()) AS age from mascota m, animales a where a.id = m.especie and m.chip = {$chip}";
                $consulta = $this->db->query($query);

                while ($filas = $consulta->fetch_assoc()) {
                    $this->species[] = $filas;
                }

                return $this->species;

      } else {

        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
      }
    }

}
