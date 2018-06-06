<?php

class medicines_model {

    public $db;
    private $medicines;
    private $med_spe;
    private $species;
    private $administration;
    private $id;
    private $name;
    private $category;

    public function __construct() {
        $this->db = Conectar::conexion();
        $this->medicies = array();
        $this->med_spe = array();
        $this->species = array();
        $this->administration = array();
        $this->category = array();
    }

    // Función que devuelve todas las medicinas
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

    // Función que devuelve los medicamentos que contengan la palabra introducida en el efecto del medicamento
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

    // función que devuelve que medicamento es aconsejado para cada animal
    public function get_species() {
        $query = "sELECT med_spe.*, a.especie FROM medicamento_especie med_spe, animales a where a.id = med_spe.id_especie";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    // función que devuelve todos los tipos de animal
    public function get_all_species() {
        $query = "SELECT * FROM animales";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->species[] = $filas;
        }
        return $this->species;
    }

    // función que devuelve las vías de administración
    public function get_way_administration() {
        $query = "SELECT * FROM administramiento_medicamento";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->administration[] = $filas;
        }
        return $this->administration;
    }

    // función quie devuelve los nombre de los medicamentos para la función autocomplete
    public function get_name_medicine_autocomplete() {
        $query = "SELECT m.*, mn.nombre as value, mn.id as data FROM medicamento m, medicamento_nombre mn where m.id = mn.id";
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    // función que devuelve los medicamentos que tengan en el nombre la palabra introducida
    public function get_name_medicine($medicine) {
        $query = "SELECT med.id, medN.nombre, cat.nombre as categoria, efe.efecto, adm.id as id_adm,
            cat.id as id_cat, efeSec.efecto_secundario, adm.administramiento, img.url, lab.marca FROM medicamento med,
            medicamento_nombre medN, categoria_medicamento cat, efecto_medicamento efe, efectos_secundarios efeSec,
            administramiento_medicamento adm, imagenes img, laboratorio lab where med.nombre = medN.id
            AND med.categoria = cat.id AND med.efecto = efe.id AND med.efecto_secundario = efeSec.id
            AND med.administramiento = adm.id AND med.imagen = img.id AND med.marca = lab.id and medN.nombre like '%{$medicine}%'";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->medicines[] = $filas;
        }
        return $this->medicines;
    }

    // función que devuelve las medicinas para una categoria
    public function get_medicine_graphics($cat) {
        $query = "SELECT m.nombre as id_medicamento, m.categoria as id_categoria, mn.nombre as nombre_medicamento, c.nombre as nombre_categoria from "
                . "medicamento m, medicamento_nombre mn, categoria_medicamento c WHERE m.nombre = mn.id and m.categoria = c.id and c.id = {$cat}";

        $consulta = $this->db->query($query);
        while ($fila = $consulta->fetch_assoc()) {
            $this->medicines[$fila['id_medicamento']] = $fila;
        }

        return $this->medicines;
    }

    // función que devuelve el nombre de la categoria buscada para la función graphics
    public function get_current_category($idCat) {
        $query = "select nombre from categoria_medicamento where id = {$idCat}";
        
        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->category[] = $filas;
        }
        return $this->category;
    }

    // función que devuelve cuantas recetas se han hecho cada mes para cada medicamento (pasandole la categoria que buscar)
    public function graphic($catMedicinas) {
        
        // se guardan las medicinas de una categoria
        $medicines = $this->get_medicine_graphics($catMedicinas);
        
        $idsMedicinas = [];
        
        foreach ($medicines as $medicine) {
            $idsMedicinas[] = $medicine['id_medicamento'];
        }
        $idsMedicinas = implode(',', $idsMedicinas);

        $query = "select medicamento as id_medicamento, SUM(cantidad) as suma, CONCAT(YEAR(fechaReceta),'-',MONTH(fechaReceta)) as MonthYear "
                . " from recetas WHERE medicamento IN ({$idsMedicinas}) group by MonthYear, id_medicamento order by fechaReceta ASC";

                
        $consulta = $this->db->query($query);
        
        $this->medicines = []; 
        
        // guarda las medicinas estructuradas por fecha como key del array -> la key del array dentro del primer array como ID el medicamento y dentro 
        //  se guarda el nombre del medicamento y la cantidad total de recetas para esa fecha
        while ($fila = $consulta->fetch_assoc()) {
            $this->medicines[$fila['MonthYear']][$fila['id_medicamento']] = ['nombre' => $medicines[$fila['id_medicamento']]['nombre_medicamento'], 'suma' => $fila['suma']];
        }
        
        return $this->medicines;
    }


}
