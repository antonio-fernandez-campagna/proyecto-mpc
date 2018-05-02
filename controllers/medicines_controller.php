<?php

//Llamada al modelo
require_once("models/medicines_model.php");

// clase que controla aádir productos, la vista de productos (la del buscador o por subcategorias), y se mostrarán las categorias
class medicines_controller {

    // Función muestra la vista de product_view.phtml
    // se le pasa el id de la subcategoria y muestra los productos por esta subcategoria
    function all_medicines() {

        $med = new medicines_model();

        $medicines = $med->get_all_medicines();
        $data['species'] = $med->get_species();

        
        $data['medicines'] = [];

        foreach ($medicines as $med) {
            $data['medicines'][$med['id']] = $med;
        }

        $i = 0;

        foreach ($data['medicines'] as $med) {

            foreach ($data['species'] as $spe) {

                if ($med['id'] == $spe['id_medicamento']) {

                    $data['medicines'][$med['id']]['especie'][$i] = $spe;
                    $i++;
                }
            }
        }


        return $data['medicines'];
    }

    function all_species() {

        $med = new medicines_model();

        $species = $med->get_all_species();

        return $species;
    }

    function all_way_administration() {

        $med = new medicines_model();

        $administration = $med->get_way_administration();

        return $administration;
    }

}
