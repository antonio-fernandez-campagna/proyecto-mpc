<?php

//Llamada al modelo
require_once("models/medicines_model.php");
require_once("controllers/medicines_controller.php");

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

    function name_medicine($medicine) {

        $med = new medicines_model();

        $data['medicines'] = $med->get_name_medicine($medicine);

        return $data['medicines'];
    }

    function medicines_name_graphic() {

        $med = new medicines_model();

        $medicines = $med->get_medicine_graphics();
        $out = [];

        foreach ($medicines as $m) {
            array_push($out, $m->nombre_medicamento);
        }

        return implode(',', $out);
    }

    function medicine_graph() {

        $med = new medicines_model();

        $idMed = $_GET['medId'];
 $idMed = 1;   //todo remove this
        $prescription = $med->get_graph($idMed);
        $out = [];
        //echo "<pre>".print_r($prescription, 1)."</pre>";

         if(array_key_exists('0', $prescription) && !empty($prescription)){
             foreach($prescription as $m){
                  $out[] = "[".strtotime($m['fecha']).",".$m['cnt']."]" ;
             }
         }else{
             $out[] = "[".time().",0]" ;
             //$out[time()] = 0;
         }

         echo "[".implode(",",$out)."]"; die;

       /*echo "<pre>".print_r($out, 1)."</pre>";
        die;
        echo json_encode($out, 1);*/
    }

}
