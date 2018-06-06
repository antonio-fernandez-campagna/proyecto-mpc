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

    // función que crea las gráficas de las medicinas.
    // crea una gráfica que muestra cuánto se ha vendido cada medicamento en un mes.
    // crea la gráfica por cada categoría.

    function medicine_graph() {

        $cat = new categories_controller();
        $med = new medicines_model();
        
        // si se ha clicado en una categoria mostrará esa, si no, por defecto mostrará la categoria con ID 1
        $catMedicine = !empty($_GET['catMedicine']) ? $_GET['catMedicine'] : 1;


        $prescription = $med->graphic($catMedicine);
                
        // se obtiene la categoria búscada para la gráfica
        $currentCategory = $med->get_current_category($catMedicine);


        // se guarda la key del array $prescription
        $fechas = array_keys($prescription);

        $medicinesId = [];
        $medicinesName = [];

        
        // guarda en el array $medicinesId el Id de los medicamentos
        // guarda en el array $medicinesName el nombre de los medicamentos como Key del array el ID del medicamento
        foreach ($prescription as $date => $values) {
            foreach ($values as $id => $medicine) {
                $medicinesId[] = $id;
                $medicinesName[$id] = $medicine['nombre'];
            }
        }
        
        
        // crea un array con los ID de los medicamentos
        $medicinesId = array_unique($medicinesId);

        // se empieza a crear la tabla que en la que se guardarán los datos de las medicinas
        $html = '<table border=1 class="highchart" data-graph-container=".. .. .highchart-container" data-graph-type="line">';
        $html .= '<caption>Número de medicamentos recetados por mes || Gráficos de la categoría: ' . $currentCategory[0]['nombre'] . '</caption>';
        $html .= '<thead><tr><th>Month</th>';
        
        // se crean los TH con los nombres de las medicinas
        foreach ($medicinesName as $name) {
            $html .= "<th>{$name}</th>";
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
                
        // crea la tabla para cada fecha la cantidad de medicamentos recetados
        foreach ($fechas as $date) {
            
            // se crean los td con las fechas
            $html .= "<tr><td>{$date}</td>";
            
            foreach ($medicinesId as $id) {       
                
                // se guarda la "suma" (la cantidad de medicamentos recetados cada mes), si no hay se pone 0;
                $val = isset($prescription[$date][$id]) ? $prescription[$date][$id]['suma'] : 0;
                
                // se introduce un "td" con la cantidad de cada medicamento para una fecha
                $html .= "<td>{$val}</td>";      
                
            }
            $html .= "</tr>";
        }
        $html .= '</tbody></table>';

        // recoge todas las categorias
        $data['categories'] = $cat->all_categories();

        // llama a la vista
        include 'views/lab_view.phtml';
    }

}
