<?php
require_once("controllers/medicines_controller.php");
require_once 'controllers/categories_controller.php';
class home_controller {

    // Función que muestra la página principal
    function view($userCat = "") {
        
        if(!empty($_SESSION['user'])){
            
            $med = new medicines_controller();
            $cat = new categories_controller();
                    
            $data['medicines'] = $med->all_medicines();                      
            $data['categories'] = $cat->all_categories();
            $data['species'] = $med->all_species();
            
            $data['administration'] = $med->all_way_administration();
            

             if (!empty($_SESSION['user']) && $_SESSION['userType'] == "veterinario" ){
                    
            require_once("views/vet_view.phtml");
             }
             
            if (!empty($_SESSION['user']) && $_SESSION['userType'] == "proveedor"){
            require_once("views/lab_view.phtml");
             }
             
             if (!empty($_SESSION['user']) && $_SESSION['userType'] == "distribuidor"){
            require_once("views/farm_view.phtml");
             }
            
        } else {
            require_once("views/home_view.phtml");
        }    
        
    }

}

?>
