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
        $data['med_spe'] = $med->get_medicine_specie();
        $data['species'] = $med->get_species();
        
        echo "<pre>".print_r($data['especies'], 1)."</pre>";
        
        $data['medicines'] = [];
        
        foreach ($medicines as $med) {
            $data['medicines'][$med['id']] = $med;
        }
        
        
        foreach ($data['medicines'] as $med) {

            foreach ($data['med_spe'] as $med_spe) {

                if ($med['id'] == $med_spe['id_medicamento']) {
                    
                    $data['medicines'][$med['id']]['especie'] = $med_spe;
                }
            }
        }

        echo "<pre>" . print_r($data['medicines'], 1) . "</pre>";


        return $data['medicines'];
    }

}

