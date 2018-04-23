<?php

require_once("models/login_model.php");
require_once("controllers/home_controller.php");

// clase que controla el Login, registro y comprueba el estado del cart
class login_controller {

    // Función para logearse
    function login() {
        $user_model = new login_model();
        $conexion = $user_model->db;

        $user = !empty($_POST['username']) ? $_POST['username'] : "";
        $contrasenya = !empty($_POST['password']) ? $_POST['password'] : "";

        $user_model->setUsername($user);
        $user_model->setPassword($contrasenya);

        $ok = $user_model->verifyUser();

         // si se ha logeado correctamente muestra la página principal y devuelve true, sino false y mostrará un intento
        // de inicio de sesión fallido
        
        if ($ok) {
            $_SESSION['user'] = $user;

            $home = new home_controller();
            $home->user_view();

            return true;
        } else {
            return false;
        }
    }

    // Función para registrarse
    function register() {


    }

    // Función que muestra un error
    function loginFailed() {

    }

}

?>
