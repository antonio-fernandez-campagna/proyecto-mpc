<?php

class home_controller {

    // Función que muestra la página principal
    function view($userCat = "") {
        
        if(!empty($_SESSION['user'])){
            
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
