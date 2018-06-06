<?php

//Llamada al modelo
require_once("models/categories_model.php");

class categories_controller {

    // Función que devuelve las categorias
    function all_categories() {

        $cat = new categories_model();

        $data['categories'] = $cat->get_all_categories();

        return $data['categories'];
    }








}

?>
