<?php

class home_controller {

    // Función que muestra la página principal
    function view() {

        require_once("views/home_view.phtml");
    }

    function user_view() {

        require_once("views/user_view.phtml");
    }


}

?>
