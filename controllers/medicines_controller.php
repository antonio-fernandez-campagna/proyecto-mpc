<?php

//Llamada al modelo
require_once("models/medicines_model.php");
require_once("controllers/medicines_controller.php");

// clase que controla lo relacionado con las medicinas(obtenerlas y creación de gráficos)
class medicines_controller {

    // Función que devuelve las medicinas (todas o por palabra buscada)
    function get_medicine($medicine = "") {

        $med = new medicines_model();

        // se llama a la función que pertoque si $medice está vacio o no (es la medicina buscada)
        if (empty($medicine)) {
            $medicines = $med->get_all_medicines();
        } else {
            $medicines = $med->get_name_medicine($medicine);
        }

        $data['species'] = $med->get_species();

        $data['medicines'] = [];

        
        // se asignan qué medicamento es recomendado para qué especie
        if (!empty($medicines)) {

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
        } else {
            return false;
        }

        return $data['medicines'];
    }

    // función que devuelve todas las especies
    function all_species() {

        $med = new medicines_model();

        $species = $med->get_all_species();

        return $species;
    }

    // función que devuelve las vías de administración
    function all_way_administration() {

        $med = new medicines_model();

        $administration = $med->get_way_administration();

        return $administration;
    }

   

    function medicine_graph() {

        $cat = new categories_controller();
        $med = new medicines_model();
        $catMedicine = !empty($_GET['catMedicine']) ? $_GET['catMedicine'] : 1;


        $prescription = $med->graphic($catMedicine);
        $out = [];

        $currentCategory = $med->get_current_category($catMedicine);

        $fechas = array_keys($prescription);
        $medicinasIds = [];
        $medicinasNames = [];
        foreach ($prescription as $date => $values) {
            foreach ($values as $k => $m) {
                $medicinasIds[] = $k;
                $medicinasNames[$k] = $m['nombre'];
            }
        }

        $medicinasIds = array_unique($medicinasIds);

        $html = '<table border=1 class="highchart" data-graph-container=".. .. .highchart-container" data-graph-type="line">';
        $html .= '<caption>Número de medicamentos recetados por mes || Gráficos de la categoría: ' . $currentCategory[0]['nombre'] . '</caption>';
        $html .= '<thead><tr><th>Month</th>';
        foreach ($medicinasNames as $mn) {
            $html .= "<th>{$mn}</th>";
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        foreach ($fechas as $d) {
            $html .= "<tr><td>{$d}</td>";
            foreach ($medicinasIds as $m) {
                $val = isset($prescription[$d][$m]) ? $prescription[$d][$m]['suma'] : 0;
                $html .= "<td>{$val}</td>";
            }
            $html .= "</tr>";
        }
        $html .= '</tbody></table>';


        $data['categories'] = $cat->all_categories();

        include 'views/lab_view.phtml';
    }

}
