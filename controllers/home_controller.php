<?php

class home_controller {

    // Función que muestra la página principal
    function view() {

        if(!empty($_SESSION['user'])){
            
             if ($_SESSION['user'] == "vet" ){
            require_once("views/vet_view.phtml");
             }
            
        } else {
            require_once("views/home_view.phtml");
        }
        
        
    }

    function user_view() {
        
        if ($_SESSION['user'] == "vet" ){
            require_once("views/vet_view.phtml");
        }
        
    }


}

?>
