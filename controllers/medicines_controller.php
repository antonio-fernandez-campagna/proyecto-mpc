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
        $catMedicine = $_GET['catMedicine'];
        
        $prescription = $med->test_func($catMedicine);
        $out = [];
        //echo "<pre>".print_r($prescription, 1)."</pre>";
        
        $fechas = array_keys($prescription);
        $medicinasIds = [];
        $medicinasNames = [];
        foreach($prescription as $date => $values){
            foreach($values as $k=>$m){
                $medicinasIds[] = $k;
                $medicinasNames[$k] = $m['nombre'];
            }
        }
        
        $medicinasIds= array_unique($medicinasIds);
   
        $html = '<table border=1 class="highchart" data-graph-container=".. .. .highchart-container" data-graph-type="line">';
        $html.= '<caption>Column example</caption>';
        $html.= '<thead><tr><th>Month</th>';
        foreach($medicinasNames as $mn){
            $html.= "<th>{$mn}</th>";
        }
        $html.= '</tr></thead>';
        $html.= '<tbody>';
        foreach($fechas as $d){
            $html.= "<tr><td>{$d}</td>";
            foreach($medicinasIds as $m){
                $val = isset($prescription[$d][$m])?$prescription[$d][$m]['suma']:0;
                $html.= "<td>{$val}</td>";
            }
            $html.= "</tr>";
        }
        $html.= '</tbody></table>';
       
        return $html;
    }

}
