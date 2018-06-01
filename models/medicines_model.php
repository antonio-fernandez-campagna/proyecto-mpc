<?php

class medicines_model {

    public $db;
    private $medicines;
    private $med_spe;
    private $species;
    private $administration;
    private $id;
    private $name;

    public function __construct() {
        $this->db = Conectar::conexion();
        $this->medicies = array();
        $this->med_spe = array();
        $this->species = array();
        $this->administration = array();
    }

    // Función que devuelve todos los productos
    public function get_all_medicines() {
        $query = "SELECT med.id, medN.nombre, cat.nombre as categoria, efe.efecto, adm.id as id_adm, cat.id as id_cat, efeSec.efecto_secundario, "
                . "adm.administramiento, img.url, lab.marca FROM medicamento med, medicamento_nombre medN, categoria_medicamento cat, efecto_medicamento efe, "
                . "efectos_secundarios efeSec, administramiento_medicamento adm, imagenes img, laboratorio lab where med.nombre = medN.id AND med.categoria = cat.id AND"
                . " med.efecto = efe.id AND med.efecto_secundario = efeSec.id AND med.administramiento = adm.id AND med.imagen = img.id AND med.marca = lab.id";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    // Función para el buscador, devuelve la descripcion corta y el ID todos los productos
    public function get_medicine_by_efect($word) {
        $query = "SELECT med.id, medN.nombre, cat.nombre as categoria, efe.efecto, efeSec.efecto_secundario, adm.administramiento, img.url, lab.marca FROM "
                . "medicamento med, medicamento_nombre medN, categoria_medicamento cat, efecto_medicamento efe, efectos_secundarios efeSec, administramiento_medicamento adm, imagenes img, laboratorio lab "
                . "where med.nombre = medN.id AND med.categoria = cat.id AND med.efecto = efe.id AND med.efecto_secundario = efeSec.id AND med.administramiento = adm.id AND med.imagen = img.id AND med.marca = lab.id
                AND efe.efecto like '%$word%'";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    public function get_species() {
        $query = "sELECT med_spe.*, a.especie FROM medicamento_especie med_spe, animales a where a.id = med_spe.id_especie";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    public function get_all_species() {
        $query = "SELECT * FROM animales";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    public function get_way_administration() {
        $query = "SELECT * FROM administramiento_medicamento";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->administration[] = $filas;
        }
        return $this->administration;
    }

    public function get_name_medicine_autocomplete() {
        $query = "SELECT m.*, mn.nombre as value, mn.id as data FROM medicamento m, medicamento_nombre mn where m.id = mn.id";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    public function get_name_medicine($medicine) {
        $query = "SELECT med.id, medN.nombre, cat.nombre as categoria, efe.efecto, efeSec.efecto_secundario, adm.administramiento, 
            img.url, lab.marca FROM medicamento med, medicamento_nombre medN, categoria_medicamento cat, efecto_medicamento efe, 
            efectos_secundarios efeSec, administramiento_medicamento adm, imagenes img, laboratorio lab
            where med.nombre = medN.id AND med.categoria = cat.id AND med.efecto = efe.id AND
            med.efecto_secundario = efeSec.id AND med.administramiento = adm.id AND
            med.imagen = img.id AND med.marca = lab.id and medN.nombre like '%$medicine%'";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    public function get_medicine_graphics($cat) {
        $query = "SELECT m.nombre as id_medicamento, m.categoria as id_categoria, mn.nombre as nombre_medicamento, c.nombre as nombre_categoria from "
                . "medicamento m, medicamento_nombre mn, categoria_medicamento c WHERE m.nombre = mn.id and m.categoria = c.id and c.id = {$cat}";

        $consulta = $this->db->query($query);
        while ($fila = $consulta->fetch_assoc()) {
            $this->medicines[$fila['id_medicamento']] = $fila;
        }
        
        return $this->medicines;
    }

    public function get_graph($idMed) {
        $query = "select count(*) as cnt, CONCAT(YEAR(fechaReceta),'-',MONTH(fechaReceta)) as MonthYear, medicamento as id_medicamento from recetas WHERE medicamento = {$idMed} group by MonthYear order by fechaReceta ASC";

        die($query);
        
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }
    
    public function test_func($catMedicinas) {
        $meds = $this->get_medicine_graphics($catMedicinas);
        $idsMedicinas = [];
        foreach($meds as $m){
            $idsMedicinas[] = $m['id_medicamento'];
        }
        $idsMedicinas = implode(',',$idsMedicinas);
        
        $query = "select medicamento as id_medicamento, SUM(cantidad) as suma, CONCAT(YEAR(fechaReceta),'-',MONTH(fechaReceta)) as MonthYear "
                . " from recetas WHERE medicamento IN ({$idsMedicinas}) group by MonthYear, id_medicamento order by fechaReceta ASC";

        $consulta = $this->db->query($query);
        $this->medicines = []; //todo change var name to be used as output storage
        while ($fila = $consulta->fetch_assoc()) {
            $this->medicines[$fila['MonthYear']][$fila['id_medicamento']] = ['nombre'=> $meds[$fila['id_medicamento']]['nombre_medicamento'],'suma'=> $fila['suma']];        
            
        }
        return $this->medicines;
    }

}
