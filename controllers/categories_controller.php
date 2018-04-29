<?php

//Llamada al modelo
require_once("models/categories_model.php");

// clase que controla aádir productos, la vista de productos (la del buscador o por subcategorias), y se mostrarán las categorias
class categories_controller {

    // Función muestra la vista de product_view.phtml
    // se le pasa el id de la subcategoria y muestra los productos por esta subcategoria
    function all_categories() {

        $cat = new categories_model();

        $data['categories'] = $cat->get_all_categories();

        return $data['categories'];
    }








}

?>
